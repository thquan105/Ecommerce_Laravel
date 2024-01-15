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
    public function index(Request $request)
    {
        // FirebaseAuth.getInstance().getCurrentUser();
        $productRef = app('firebase.firestore')->database()->collection('product');
        $products = $productRef->documents();

        // $productsbyCategory = $productRef->where('idCategory', '=', $request->button('id'))->documents();

        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function productDetails(Request $request)
    {
        $idproduct = $request->route('id');

        $productRef = app('firebase.firestore')->database()->collection('product');
        $productdatas = $productRef->document($idproduct)->snapshot();
        $idShop = $productdatas['idShop'];
        $shop = app('firebase.firestore')->database()->collection('user')->document($idShop)->snapshot();

        $reviews = $productRef->document($idproduct)->collection('review')
        ->orderBy('atCreate', 'desc')
        ->documents();

        $imgProductRef = $productRef->document($idproduct)->collection('image');
        $optionProductRef = $productRef->document($idproduct)->collection('option');
        $images = $imgProductRef->documents();
        $options = $optionProductRef->documents();
        // dd($options);
        return view('frontend.products.detail', compact('shop', 'productdatas', 'images', 'options', 'reviews'));
    }
    public function purchased(){
        $ordertRef = app('firebase.firestore')->database()->collection('order');
        $orders = $ordertRef->where('idUser', '=', session()->get('uid'))->documents();

        return view('frontend.products.purchased', compact('orders'));
    }

    public function review(Request $request){
        $request->validate([
            'review' => 'required',
            'rating' => 'required',
        ]);
        $productRef = app('firebase.firestore')->database()->collection('product')->document($request->input('idproduct'));
        $reviewRef = $productRef->collection('review');
        $reviewRef->add([
            'idUser' => session()->get('uid'),
            'content' => $request->review,
            'rating' => (int)$request->rating,
            'atCreate' => now(),
        ]);
        return redirect()->back()->with('alert','Đánh giá thành công !');
    }
}
