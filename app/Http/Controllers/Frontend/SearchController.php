<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Cocur\Slugify\Slugify;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $keyword = $request->search;


        // TH1: Tìm cả cụm "Giày Nam"
        $result1 = $this->searchByPhrase($keyword);

        // TH2: Tìm theo từng từ "giày" và "nam"
        if (isset($result1)) {
            $keywords = explode(' ', $keyword);
            $result2 = $this->searchByKeywords($keywords);

            // TH3: Tìm theo chữ cái đầu tiền "g"
            if (isset($result2)) {
                $firstChar = mb_substr($keyword, 0, 1, 'UTF-8');
                $result3 = $this->searchByFirstChar($firstChar);

                // Kiểm tra kết quả cuối cùng
                if ($result3) {
                    $products = $result3;
                }
            } else {
                $products = $result2;
            }
        } else {
            $products = $result1;
        }

        return view('frontend.products.search', compact('products'));
    }

    public function searchByPhrase($phrase)
    {
        // Sử dụng Eloquent để tìm kiếm theo cụm từ
        $products = app('firebase.firestore')->database()->collection('product');
        return $products->where('name', '=', $phrase)->documents();
    }

    public function searchByKeywords($keywords)
    {
        // Sử dụng Eloquent để tìm kiếm theo từ khóa
        $products = app('firebase.firestore')->database()->collection('product');

        foreach ($keywords as $keyword) {
            $query = $products->where('name', 'in',);
        }

        return $query->documents();
    }

    public function searchByFirstChar($char)
    {
        // Sử dụng Eloquent để tìm kiếm theo chữ cái đầu tiên
        $products = app('firebase.firestore')->database()->collection('product');

        return $products->where($char, 'in', 'name')->documents();
    }
}
