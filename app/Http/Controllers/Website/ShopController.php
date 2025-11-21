<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){
        $products = product::with('variants')->paginate(12);
        return view('Website.shop.index', compact('products'));
    }
    public function show($id){
        $product = product::with('variants')->findOrFail($id);
        return view('Website.shop.show', compact('product'));
    }
}
