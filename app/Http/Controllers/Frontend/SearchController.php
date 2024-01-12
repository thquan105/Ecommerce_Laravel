<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Normalizer;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $keyword = $request->search;
        $prodRef = app('firebase.firestore')->database()->collection('product');
        $products = $prodRef->where('name', '>=', $keyword . "")
        ->documents();
        return view('frontend.products.search', compact('products', 'keyword'));
    }
}
