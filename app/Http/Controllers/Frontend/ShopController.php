<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop_index($idShop)
    {
        // FirebaseAuth.getInstance().getCurrentUser();
        $shoper = app('firebase.firestore')->database()->collection('user')->document($idShop);
        $products = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', $idShop)
            ->documents();
        $categories = $shoper->collection('categoryShop')->documents();

        return view('seller.shop', compact('shoper', 'products', 'categories'));
    }

    public function shop_category($idShop, $idCate)
    {
        $shoper = app('firebase.firestore')->database()->collection('user')->document($idShop);
        $products = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', $idShop)
            ->where('idCategoryShop', '=', $idCate)
            ->documents();
        $categories = $shoper->collection('categoryShop')->documents();

        return view('seller.shop', compact('shoper', 'products', 'categories'));
    }

    public function product_asc($idShop)
    {
        $shoper = app('firebase.firestore')->database()->collection('user')->document($idShop);
        $products = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', $idShop)
            ->orderBy('price', 'asc')
            ->documents();
        $categories = $shoper->collection('categoryShop')->documents();

        return view('seller.shop', compact('shoper', 'products', 'categories'));
    }

    public function product_desc($idShop)
    {
        $shoper = app('firebase.firestore')->database()->collection('user')->document($idShop);
        $products = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', $idShop)
            ->orderBy('price', 'desc')
            ->documents();  
        $categories = $shoper->collection('categoryShop')->documents();

        return view('seller.shop', compact('shoper', 'products', 'categories'));
    }

    public function product_filter(Request $request, $idShop)
    {
        $shoper = app('firebase.firestore')->database()->collection('user')->document($idShop);
        $query = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', $idShop);

        // Kiểm tra và thêm điều kiện lọc nếu giá trị tồn tại
        if (isset($request->min_price)) {
            $query = $query->where('price', '>=', (int)$request->min_price);
        }

        if (isset($request->max_price)) {
            $query = $query->where('price', '<=', (int)$request->max_price);
        }

        $products = $query->documents();
        $categories = $shoper->collection('categoryShop')->documents();

        return view('seller.shop', compact('shoper', 'products', 'categories'));
    }
}
