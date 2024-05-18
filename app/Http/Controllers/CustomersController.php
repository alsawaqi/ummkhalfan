<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $customerId = optional($user->customer)->id;

            return response()->json(Customers::where('id', $customerId)->first());
        }
    }

    public function update(Request $request)
    {
        $customer = Customers::where('user_id', Auth::id())->first();

        if ($customer) {
            $request->validate([
                'first_name' => 'required|string|max:255',  // Example validation rule for name
                'last_name' => 'required|string|max:255',  // Example validation rule for age
                'phone' => 'required|string|unique:customers,phone,'.$customer->id,
              ]);
            if ($request->has('phone') && $request->phone != $customer->phone) {
                $customer->phone = $request->phone;
            }

            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;

            $customer->save();

            return response()->json($request->first_name);
        } else {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|unique:customers,phone',
              ]);

            Customers::create([
                              'user_id' => Auth::id(),
                              'first_name' => $request->first_name,
                              'last_name' => $request->last_name,
                              'phone' => $request->phone,
                            ]);
        }
    }
}
