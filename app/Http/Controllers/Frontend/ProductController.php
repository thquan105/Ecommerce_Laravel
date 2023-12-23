<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class ProductController extends Controller
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
    public function productDetails(Request $request)
    {
        $idproduct = $request->route('id');

        $productRef = app('firebase.firestore')->database()->collection('product');
        $productdatas = $productRef->document($idproduct)->snapshot()->data();
        $idShop = $productdatas['idShop'];
        $shop = app('firebase.firestore')->database()->collection('user')->document($idShop)->snapshot()->data();

        $imgProductRef = $productRef->document($idproduct)->collection('image');
        $images = $imgProductRef->documents();
        return view('frontend.products.detail', compact('shop', 'productdatas', 'images'));
    }
}