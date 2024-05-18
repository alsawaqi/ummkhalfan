<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Products::with(['categories:id,name,slug',
                                    'firstImage:id,product_id,name,path', ])
                                    ->where('stock', '>', 0)
                                    ->paginate(10);

        return response()->json(['data' => $product]);
    }

    public function getfeatured()
    {
        return response()->json(
            Products::with(['categories', 'firstImage:id,product_id,name,path'])
            ->where('stock', '>', 0)
            ->limit(8)
            ->orderBy('id', 'desc')
            ->get()
        );
    }

    public function show(Products $products)
    {
        return response()->json($products->load('firstImage:id,product_id,name,path', 'categories'));
    }

    public function shows(Products $product)
    {
        return response()->json($product->load('firstImage:id,product_id,name,path', 'categories'));
    }
}
