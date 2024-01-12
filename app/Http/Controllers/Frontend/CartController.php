<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //   $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userRef = app('firebase.firestore')->database()->collection('user');
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'))->snapshot();

        $carts = $userRef->document(session()->get('uid'))->collection('cart')->documents();
        return view('frontend.carts.index', compact('user', 'carts'));
    }
    public function addToCart(Request $request)
    {
        $userRef = app('firebase.firestore')->database()->collection('user');
        $carts = $userRef->document(session()->get('uid'))->collection('cart');
        $carts->add([
            'optionProductId' => $request->input('idoption'),
            'productId' => $request->input('idproduct'),
            'quantity' => (int)$request->input('num-product'),
        ]);
        return redirect()->back()->with([
            'message' => 'Thêm thành công !',
            'alert-type' => 'success'
        ]);
    }


    public function update(Request $request)
    {
        // Cart::instance('cart')->update($request->rowId, $request->quantity);
        $userRef = app('firebase.firestore')->database()->collection('user');

        $carts = $userRef->document(session()->get('uid'))->collection('cart')->document($request->rowId);

        $carts->update([
            ['path' => 'quantity', 'value' => $request->quantity],
        ]);

        return redirect()->route('carts.index');
    }

    public function destroy($id)
    {
        $userRef = app('firebase.firestore')->database()->collection('user');

        $carts = $userRef->document(session()->get('uid'))->collection('cart')->document($id)->delete();

        return redirect()->route('carts.index')->with([
            'message' => 'Sản phẩm đã được xóa thành công !',
            'alert-type' => 'danger'
        ]);
    }
}
