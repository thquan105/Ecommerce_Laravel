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
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        return view('seller.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([   
            'parent_id' => 'required',
            'subparent_id' => 'required',
            'name' => 'required',
        ], [
            'parent_id.required' => 'Danh mục chính là bắt buộc.',
            'subparent_id.required' => 'Danh mục phụ là bắt buộc.',
            'name.required' => 'Tên danh mục của shop là bắt buộc.',
        ]);
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $user->collection('categoryShop')->add([
            'name' => $request->name,
            'idCategory' => $request->parent_id,
            'idSubCategory' => $request->subparent_id,
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
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        $parent_category = app('firebase.firestore')->database()->collection('category')->document($shopCategories->data()['idCategory']);
		$subcategoryList = $parent_category->collection('subcategory')->documents();
        return view('seller.subcategories.edit', compact('categories', 'shopCategories', 'subcategoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category)
    {
        $request->validate([   
            'parent_id' => 'required',
            'subparent_id' => 'required',
            'name' => 'required',
        ], [
            'parent_id.required' => 'Danh mục chính là bắt buộc.',
            'subparent_id.required' => 'Danh mục phụ là bắt buộc.',
            'name.required' => 'Tên danh mục của shop là bắt buộc.',
        ]);
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $categories = $user->collection('categoryShop')
                ->document($category)
                ->update([
                    ['path' => 'name', 'value' => $request->name],
                    ['path' => 'idCategory', 'value' => $request->parent_id],
                    ['path' => 'idSubCategory', 'value' => $request->subparent_id],
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

    public function subcategories(Request $request)
	{
		$Subcategories = $this->getSubcategories($request->category_id);
        // dd($Subcategories);
		return response()->json(['subcategories' => $Subcategories]);
	}
}
