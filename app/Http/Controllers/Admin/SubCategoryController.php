<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use Kreait\Firebase\Request as FirebaseRequest;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $subcategory = app('firebase.firestore')
            ->database()
            ->collection('category')
            ->document($id);
        $Cate = $subcategory->snapshot();
        $subcategories = $subcategory->collection('subcategory')->documents();
        return view('admin.categories.subcategories.index', compact('subcategories', 'Cate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id_old)
    {
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        return view('admin.categories.subcategories.create', compact('categories', 'id_old'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        $subcategory = app('firebase.firestore')
            ->database()
            ->collection('category')
            ->document($request->parent_id)
            ->collection('subcategory');
        $subcategory->add([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.subcategories.index', $request->parent_id)->with([
            'message' => 'Thêm thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoryId, $subcategoryId)
    {
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        $subcategories = app('firebase.firestore')
            ->database()
            ->collection('category')
            ->document($categoryId)
            ->collection('subcategory');
        $subcategory = $subcategories->document($subcategoryId)->snapshot();
        return view('admin.categories.subcategories.edit', compact('categories', 'subcategory', 'categoryId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cateId, $subcateId)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        if ($request->parent_id == $cateId) {
            $subcategory = app('firebase.firestore')
                ->database()
                ->collection('category')
                ->document($request->parent_id)
                ->collection('subcategory');
            $subca = $subcategory->document($subcateId);
            $subca->update([
                ['path' => 'name', 'value' => $request->name],
            ]);
        } else {
            $subcategory_del = app('firebase.firestore')
                ->database()
                ->collection('category')
                ->document($cateId)
                ->collection('subcategory')
                ->document($subcateId)->delete();
            $subcategory = app('firebase.firestore')
                ->database()
                ->collection('category')
                ->document($request->parent_id)
                ->collection('subcategory');
            $subcategory->add([
                'name' => $request->name,
            ]);
        }

        return redirect()->route('admin.subcategories.index', $request->parent_id)->with([
            'message' => 'Sửa thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $cateId, $subcateId)
    {
        $subcategory_del = app('firebase.firestore')
                ->database()
                ->collection('category')
                ->document($cateId)
                ->collection('subcategory')
                ->document($subcateId)->delete();
        return redirect()->back()->with([
            'message' => 'Đã xóa !',
            'alert-type' => 'danger'
        ]);
    }
}
