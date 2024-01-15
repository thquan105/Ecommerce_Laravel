<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $wishlists = WishList::where('user_id', auth()->id())
        // 	->orderBy('created_at', 'desc')->get();
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $wishlists = $user->collection('wishList')->documents();
        //dd($wishlists);
        return view('frontend.wishlists.index', compact('wishlists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!session()->has('uid')) {
            return response()->json([
                'status' => 401
            ]);
        }
        $request->validate(
            [
                'product_slug' => 'required',
            ]
        );

        $productRef = app('firebase.firestore')->database()->collection('product');
        $product = $productRef->document($request->get('product_slug'))->snapshot();

        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $wishlists = $user->collection('wishList')
            ->where('product_id', '=', $product->id())
            ->documents();

        if ($wishlists->size() > 0) {
        	return response('The product already exists !', 422);
        } else {
        	$user->collection('wishList')->add([
        		'product_id' => $product->id(),
                'create_at' => now(),
        	]);
        }

        return response($product->data()['name']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($wishlist)
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $wishlists = $user->collection('wishList')
            ->document($wishlist)->delete();

        return redirect('wishlists')->with([
            'message' => 'Successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function wishlistCount()
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $wishlists = $user->collection('wishList')
            ->documents();
        $wishlistcount = $wishlists->size();
        return response()->json([
            'count' => $wishlistcount
        ]);
    }
}
