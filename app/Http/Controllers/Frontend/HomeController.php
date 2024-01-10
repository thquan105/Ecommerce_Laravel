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

class HomeController extends Controller
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
        // FirebaseAuth.getInstance().getCurrentUser();
        $products = app('firebase.firestore')->database()->collection('product')->documents();
        $categories = app('firebase.firestore')->database()->collection('category')->documents();


        if (session()->has('uid')) {
            $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'))->snapshot();
            return view('frontend.home', compact('user', 'products', 'categories'));
        }
        return view('frontend.home', compact('products', 'categories'));
    }

    public function CartProducts(Request $request)
    {

    }
}
