<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ImageUploadingTrait;

class ProductController extends Controller
{
    use ImageUploadingTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = app('firebase.firestore')->database()->collection('product')
            ->where('idShop', '=', session()->get('uid'))
            ->documents();

        return view('seller.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'));
        $categories = app('firebase.firestore')->database()->collection('category')->documents();
        $ShopCategories = $user->collection('categoryShop')->documents();
        return view('seller.products.create', compact('categories', 'ShopCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required'],
            'subparent_id' => ['required'],
            'cateShop_id' => ['required'],
            'description' => ['required'],
            'gallery' => ['required'],
            'attributeName' => 'required',
            'attributePrice' => 'required',
            'attributeQuantity' => 'required',
            'attributeImage' => 'required',
        ]);
        
        $productRef = app('firebase.firestore')->database()->collection('product');
        $product = $productRef->add([
            'name' => $request->name,
            'idShop' => session()->get('uid'),
            'idCategoryShop' => $request->cateShop_id,
            'idCategory' => $request->parent_id,
            'idSubCategory' => $request->subparent_id,
            'description' => $request->description,
            'atCreate' => now(),
        ]);
        // Add Images
        $localfolder = storage_path('tmp/uploads') . '/';
        $firebase_storage_path = 'productImg/';
        $productImgRef = $product->collection('image');
        foreach ($request->input('gallery', []) as $file) {
            $uploadedfile = fopen($localfolder . $file, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);

            $storage_bucket = config('services.firebase.storage_bucket');
            $imageDownloadUrl = 'https://storage.googleapis.com/' . $storage_bucket . '/' . $firebase_storage_path . $file;
            // dd($imageDownloadUrl);
            $productImgRef->add([
                'url' => $imageDownloadUrl,
            ]);
        }
        // Add Attributes
        // Xử lý thông tin attributes
        $attributeNames = $request->input('attributeName');
        $attributePrices = $request->input('attributePrice');
        $attributeQuantities = $request->input('attributeQuantity');
        $attributeImages = $request->file('attributeImage');
        if ($attributeImages && count($attributeImages) > 0) {
            $minPrice = min($attributePrices);
            $product->update([
                ['path' => 'price', 'value' => (int)$minPrice],
            ]);
        }
        $productAttriRef = $product->collection('option');
        foreach ($attributeNames as $key => $attributeName) {
            if ($attributeImages[$key]) {
                $fileImg = $attributeImages[$key];
                $name = uniqid() . '_' . trim($fileImg->getClientOriginalName());
                $fileImg->move($localfolder, $name);

                $uploadedfile = fopen($localfolder . $name, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $name]);

                $storage_bucket = config('services.firebase.storage_bucket');
                $imageDownloadUrl = 'https://storage.googleapis.com/' . $storage_bucket . '/' . $firebase_storage_path . $name;
            }
            $productAttriRef->add([
                'name' => $attributeName,
                'price' => (int)$attributePrices[$key],
                'quantity' => (int)$attributeQuantities[$key],
                'image' => $imageDownloadUrl,
            ]);
        }

        // Lấy danh sách tất cả các file trong thư mục
        $files = glob($localfolder . '/*');

        // Lặp qua từng file và xóa nó
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return redirect()->route('seller.products.index')->with([
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
    public function edit($productId)
    {
        $firestore = app('firebase.firestore')->database();
        $user = $firestore->collection('user')->document(session()->get('uid'));
        $product = $firestore->collection('product')->document($productId)->snapshot();
        $categories = $firestore->collection('category')->documents();
        $subcategories = $firestore->collection('category')->document($product->data()['idCategory'])->collection('subcategory')->documents();
        $ShopCategories = $user->collection('categoryShop')->documents();
        return view('seller.products.edit', compact('product' ,'categories', 'subcategories', 'ShopCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            'subparent_id' => ['required'],
            'cateShop_id' => ['required'],
            'description' => ['required'],
        ]);
        
        $productRef = app('firebase.firestore')->database()->collection('product')->document($id);
        $productRef->update([
            ['path' => 'name', 'value' => $request->name],
            ['path' => 'idCategory', 'value' => $request->parent_id],
            ['path' => 'idSubCategory', 'value' => $request->subparent_id],
            ['path' => 'description', 'value' => $request->description],
        ]);
        return redirect()->route('seller.products.index')->with([
            'message' => 'Lưu thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('seller.products.index')->with([
            'message' => 'Đang phát triển !',
            'alert-type' => 'success'
        ]);
    }

    public function subcategories(Request $request)
    {
        $Subcategories = $this->getSubcategories($request->category_id);
        // dd($Subcategories);
        return response()->json(['subcategories' => $Subcategories]);
    }
}
