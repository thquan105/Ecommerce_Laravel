<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('path');

        $firebase_storage_path = 'categoryImg/';
        $name     = 'categori_img';
        $localfolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $file      = $name . '_' . time() . '.' . $extension;
        if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder . $file, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);

            $storage_bucket = config('services.firebase.storage_bucket');
            $imageDownloadUrl = 'https://storage.googleapis.com/' . $storage_bucket . '/' . $firebase_storage_path . $file;
            // dd($imageDownloadUrl);
            $categoriRef = app('firebase.firestore')->database()->collection('category');
            $categoriRef->add([
                'name' => $request->name,
                'photo' => $imageDownloadUrl,
            ]);

            //will remove from local laravel folder  
            unlink($localfolder . $file);

            return redirect()->route('admin.categories.index')->with([
                'message' => 'Thêm thành công !',
                'alert-type' => 'success'
            ]);
        } else {
            return redirect()->route('admin.categories.index')->with([
                'message' => 'Lỗi !',
                'alert-type' => 'danger'
            ]);
        }
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
    public function edit($id)
    {
        $category = app('firebase.firestore')->database()->collection('category')->document($id)->snapshot();
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        if ($request->has('path')) {
            $request->validate([
                'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            $image = $request->file('path');

            $firebase_storage_path = 'categoryImg/';
            $name     = 'categori_img';
            $localfolder = public_path('firebase-temp-uploads') . '/';
            $extension = $image->getClientOriginalExtension();
            $file      = $name . '_' . time() . '.' . $extension;
            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);

                $storage_bucket = config('services.firebase.storage_bucket');
                $imageDownloadUrl = 'https://storage.googleapis.com/' . $storage_bucket . '/' . $firebase_storage_path . $file;
                // dd($imageDownloadUrl);
                $categoriRef = app('firebase.firestore')->database()->collection('category')->document($id);

                $URLphoto = $categoriRef->snapshot()->data()['photo'];

                $path = parse_url($URLphoto, PHP_URL_PATH);
                $filedel = basename($path);

                $imageDeleted = app('firebase.storage')->getBucket()->object("categoryImg/" . $filedel)->delete();

                $categoriRef->update([
                    ['path' => 'name', 'value' => $request->name],
                    ['path' => 'photo', 'value' => $imageDownloadUrl],
                ]);

                //will remove from local laravel folder  
                unlink($localfolder . $file);
            } else {
                return redirect()->route('admin.categories.index')->with([
                    'message' => 'Lỗi !',
                    'alert-type' => 'danger'
                ]);
            }
        } else {
            $categoriRef = app('firebase.firestore')->database()->collection('category')->document($id);
            $categoriRef->update([
                ['path' => 'name', 'value' => $request->name],
            ]);
        }

        return redirect()->route('admin.categories.index')->with([
            'message' => 'Sửa thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = app('firebase.firestore')->database()->collection('category')->Document($id);
        $URLphoto = $category->snapshot()->data()['photo'];
        // Trích xuất phần path từ URL
        $path = parse_url($URLphoto, PHP_URL_PATH);

        // Lấy tên tệp cuối cùng từ đường dẫn
        $filename = basename($path);

        $imageDeleted = app('firebase.storage')->getBucket()->object("categoryImg/" . $filename)->delete();
        if ($category->collection('subcategory')->documents() != null){
            $subcategories = $category->collection('subcategory')->documents();
            foreach ($subcategories as $subcategory) {
                $subcategory->reference()->delete();
            }
        }
        $category->delete();
        return redirect()->back()->with([
            'message' => 'Đã xóa !',
            'alert-type' => 'danger'
        ]);
    }
}
