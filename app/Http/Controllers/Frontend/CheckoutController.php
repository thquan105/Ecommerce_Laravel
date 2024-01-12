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
        // dd($request->all());
        $idproducts = $request->input('idproduct');
        $idshops = $request->input('idshop');
        $idoptions = $request->input('idoption');
        $idShippingUnit = $request->input('idship');
        $numprod = $request->input('numprod');

        $orderRef = app('firebase.firestore')->database()->collection('order');
        $prodRef = app('firebase.firestore')->database()->collection('product');  
        $priceShip = app('firebase.firestore')->database()->collection('shippingUnit')->document($idShippingUnit)->snapshot()->data()['price']; 
        foreach ($idshops as $idshop) {
            $order = $orderRef->add([
                'idUser' => session()->get('uid'),
                'idShippingUnit' => $idShippingUnit,
                'status' => 'đang chờ xử lý',
                'atCreate' => now(),
                'idShop' => $idshop,
            ]);
            foreach ($idproducts as $key => $idproduct) {
                $prod = $prodRef->document($idproduct);
                $optionRef = $prod->collection('option')->document($idoptions[$key]);
                $option = $optionRef->snapshot();
                $orderDetailRef = $order->collection('option');
                if ($idshop == $prod->snapshot()->data()['idShop']){
                    $orderDetailRef->add([
                        'idOption' => $idoptions[$key],
                        'idProduct' => $idproduct,
                        'quantity' => (int)$numprod[$key],
                        'price' => (int)$option->data()['price'],
                    ]);
                    $prod->update([
                        ['path' => 'sold', 'value' => (int)$prod->snapshot()->data()['sold'] + (int)$numprod[$key]],
                    ]);
                    $optionRef->update([
                        ['path' => 'quantity', 'value' => (int)$option->data()['quantity'] - (int)$numprod[$key]],
                    ]);
                    $orderRef->document($order->id())->update([
                        ['path' => 'totalByShop', 'value' => (int)$option->data()['price'] * (int)$numprod[$key] + (int)$priceShip],
                    ]);
                }       
            }
        }

        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $cartRef = $user->collection('cart')->documents();
        foreach ($cartRef as $cart) {
            $cart->reference()->delete();
        }
        return redirect()->route('home')->with([
            'message' => 'Đặt hàng thành công !',
            'alert-type' => 'success'
        ]);
    }
}
