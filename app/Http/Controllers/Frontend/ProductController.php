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
    public function index()
    {
        // FirebaseAuth.getInstance().getCurrentUser();
        $products = app('firebase.firestore')->database()->collection('product')->documents();

        $categories = app('firebase.firestore')->database()->collection('category')->documents();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function productDetails(Request $request)
    {
        $idproduct = $request->route('id');

        $productRef = app('firebase.firestore')->database()->collection('product');
        $productdatas = $productRef->document($idproduct)->snapshot()->data();
        $idShop = $productdatas['idShop'];
        $shop = app('firebase.firestore')->database()->collection('user')->document($idShop)->snapshot()->data();

        $imgProductRef = $productRef->document($idproduct)->collection('image');
        $optionProductRef = $productRef->document($idproduct)->collection('option');
        $images = $imgProductRef->documents();
        $options = $optionProductRef->documents();
        return view('frontend.products.detail', compact('shop', 'productdatas', 'images', 'options'));
    }
}
