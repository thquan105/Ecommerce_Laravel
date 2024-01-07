<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $categories = $user->collection('categoryShop')->documents();
        return view('seller.subcategories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.subcategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([  
            'name' => 'required',
        ], [
            'name.required' => 'Tên danh mục của shop là bắt buộc.',
        ]);
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $user->collection('categoryShop')->add([
            'name' => $request->name,
        ]);
        return redirect()->route('seller.categories.index')->with([
            'message' => 'Thêm thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit($subcateId)
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $shopCategories = $user->collection('categoryShop')
                ->document($subcateId)->snapshot();
        return view('seller.subcategories.edit', compact('shopCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category)
    {
        $request->validate([   
            'name' => 'required',
        ], [
            'name.required' => 'Tên danh mục của shop là bắt buộc.',
        ]);
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $categories = $user->collection('categoryShop')
                ->document($category)
                ->update([
                    ['path' => 'name', 'value' => $request->name],
                ]);
        return redirect()->route('seller.categories.index')->with([
            'message' => 'Cập nhật thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subcateId)
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $categories = $user->collection('categoryShop')
                ->document($subcateId)
                ->delete();
        return redirect()->back()->with([
            'message' => 'Đã xóa !',
            'alert-type' => 'danger'
        ]);
    }
}
