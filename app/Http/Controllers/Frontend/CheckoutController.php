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

class CheckoutController extends Controller
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
        $idproducts = $request->input('idproduct');
        $idoptions = $request->input('idoption');
        $idShippingUnit = $request->input('idShippingUnit');
        $totalByShop = $request->input('totalByShop');
        $numprod = $request->input('num-product1');

        $userRef = app('firebase.firestore')->database()->collection('user');
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'))->snapshot();
        $prodRef = app('firebase.firestore')->database()->collection('product');


        return view('frontend.carts.checkout', compact('user', 'idproducts', 'idoptions', 'idShippingUnit', 'totalByShop', 'numprod'));
    }
    public function checkout(Request $request)
    {

        return redirect()->back()->with([
            'message' => 'Thêm thành công !',
            'alert-type' => 'success'
        ]);
    }
}
