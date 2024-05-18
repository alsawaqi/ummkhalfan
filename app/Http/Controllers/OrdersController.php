<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\OrdersLocation;
use App\Models\Products;
use App\Models\StockMovements;
use App\Notifications\OrdersCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    private function addOrdinalNumberSuffix($num)
    {
        if (!in_array($num % 100, [11, 12, 13])) {
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:  return $num.'st';
                case 2:  return $num.'nd';
                case 3:  return $num.'rd';
            }
        }

        return $num.'th';
    }

    public function reference()
    {
        // Step 1: Get current year and month
        $currentYearMonth = date('ym');

        // Step 2: Retrieve the last order's reference number
        $lastOrder = Orders::orderBy('created_at', 'desc')->first();
        $lastRefNumber = $lastOrder ? $lastOrder->reference : null;

        // Step 3: Calculate new reference number
        $newOrderNumber = '001'; // default order number
        if ($lastRefNumber) {
            $lastYearMonth = substr($lastRefNumber, 0, 4);
            $lastOrderNumber = intval(substr($lastRefNumber, 4));

            if ($currentYearMonth == $lastYearMonth) {
                $newOrderNumber = sprintf('%03d', $lastOrderNumber + 1);
            }
        }

        return $currentYearMonth.$newOrderNumber;
    }

    public function index()
    {
        $customer = Customers::where('user_id', Auth::id())->first();

        $orders = Orders::where('customer_id', $customer->id)->orderBy('id', 'desc')->paginate(5);

        return response()->json(['data' => $orders]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $cartItems = json_decode($request->cart, true);

            // customer info from vue
            $custom = json_decode($request->customer, true);

            $customer = Customers::where('user_id', Auth::id())->first();

            if ($custom['phone'] && $custom['phone'] != $customer->phone || $customer->phone == null) {
                $phoneExists = Customers::where('phone', $custom['phone'])
                ->where('id', '!=', $customer->id) // Exclude the current customer
                ->exists();
                if ($phoneExists) {
                    throw new \Exception('Phone number is already used by another customer');
                }

                $customer->phone = $custom['phone'];
            }

            $customer->first_name = $custom['first_name'];
            $customer->last_name = $custom['last_name'];

            $customer->save();

            $order = Orders::create([
                'reference' => $this->reference(),
                'customer_id' => $customer->id,
                'sub_total' => $request->input('sub_total'),
                'vat' => $request->input('vat'),
                'total_amount' => $request->input('sub_total'),
                'payment_type' => $request->input('payment'),
            ]);

            if ($request->filled('longitude') && $request->filled('latitude')) {
                OrdersLocation::create([
                 'orders_id' => $order->id,
                 'longitude' => $request->longitude,
                 'latitude' => $request->latitude,
                ]);
            }

            $day = date('d', strtotime('now')); // Day of the month
            $dayName = date('l', strtotime('now')); // Day of the week
            $monthName = date('F', strtotime('now')); // Month name
            $year = date('Y', strtotime('now')); // Y
            $dayWithSuffix = $this->addOrdinalNumberSuffix($day);

            foreach ($cartItems as $item) {
                $product = Products::where('id', $item['productId'])->lockForUpdate()->first();

                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception('Product stock insufficient or product not found');
                }

                $ordersitem = OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $item['quantity'],
                ]);

                StockMovements::create([
                        'products_id' => $product->id,
                        'order_items_id' => $ordersitem->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'action_type' => 'order',
                        'remark' => 'on '.$dayWithSuffix.','.$dayName.' of '.$monthName.' '.$year.', '.$custom['first_name'].' '.$custom['last_name'].' of customer id '.$customer->id.' ordered '.$item['quantity'].' '.$product->name.'',
                    ]);

                DB::table('products')
                  ->where('id', $product->id)
                  ->decrement('stock', $item['quantity']);
            }

            request()->user()->notify(new OrdersCompleted($order));
        });
    }

    public function show(Orders $orders)
    {
        $user = Auth::user();
        $customerId = optional($user->customer)->id;

        $orderWithItems = Orders::with('orderitems', 'orderitems.product', 'orderitems.product.firstImage')
                                ->where('customer_id', $customerId)
                                ->findOrFail($orders->id);

        return response()->json(['data' => $orderWithItems]);
    }
}
