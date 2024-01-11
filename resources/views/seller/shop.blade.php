@extends('frontend.layout')

@section('title', 'Profile')

@section('content')
    <section class="bg0 p-t-10 mx-auto">
        <div class="container">
            <!-- Profile widget -->
            <div class="bg-white shadow rounded overflow-hidden">
                <div class="px-4 p-t-15 pb-4 bg-dark">
                    <div class="media align-items-end profile-header">
                        <div class="profile mr-3"><img
                                src="{{ $shoper->snapshot()->data()['photo'] ?? asset('frontend/images/avatar-01.jpg') }}"
                                width="130" class="rounded mb-2 img-thumbnail"></div>
                        <div class="media-body mb-5 text-white">
                            <h4 class="mt-0 mb-0">{{ $shoper->snapshot()->data()['shopName'] ?? 'Love Shop' }}</h4>
                            <p class="small mb-0"> <i
                                    class="fa fa-map-marker mr-2"></i>{{ $shoper->snapshot()->data()['address'] ?? 'Hải Châu, Đà Nẵng' }}
                            </p>
                            <p class="small mb-2"> <i
                                    class="fa fa-phone mr-2"></i>{{ $shoper->snapshot()->data()['mobileNo'] ?? '+84123569784' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-4 d-flex justify-content-end text-center">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            @php
                                $productsize = app('firebase.firestore')
                                    ->database()
                                    ->collection('product')
                                    ->where('idShop', '=', $shoper->snapshot()->id())
                                    ->documents();
                            @endphp
                            <h5 class="font-weight-bold mb-0 d-block">{{ $productsize->size() }}</h5><small
                                class="text-muted">
                                <i class="fa fa-picture-o mr-1"></i>Products</small>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="font-weight-bold mb-0 d-block">84K</h5><small class="text-muted"> <i
                                    class="fa fa-user-circle-o mr-1"></i>Followers</small>
                        </li>
                    </ul>
                </div>
            </div><!-- End profile widget -->
            <div class="container">
    </section>
    <!-- Product -->
    <!-- Content page -->
    <section class="bg0 p-t-32 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3 p-b-80">
                    <div class="side-menu">
                        <div class="bor17 of-hidden pos-relative">
                            <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search"
                                placeholder="Search">

                            <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                                <i class="zmdi zmdi-search"></i>
                            </button>
                        </div>

                        <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Categories
                            </h4>

                            <ul>
                                <li class="bor18">
                                    <a href="{{ route('shop.index', $shoper->snapshot()->id()) }}"
                                        class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        All Products
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    <li class="bor18">
                                        <a href="{{ route('shop.category', ['id' => $shoper->snapshot()->id(), 'idCate' => $category->id()]) }}"
                                            class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                            {{ $category->data()['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-t-65">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Price Products
                            </h4>
                            <form action="{{ route('shop.product_filter', ['id'=>$shoper->snapshot()->id()]) }}" method="GET">
                                <label for="min_price">Min Price:</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" min="0" name="min_price"
                                        value="{{ request('min_price', 0) }}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"> VNĐ</span>
                                    </div>
                                </div>

                                <label for="max_price">Max Price:</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" min="0" name="max_price"
                                        value="{{ request('max_price', 50000) }}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"> VNĐ</span>
                                    </div>
                                </div>

                                <button class="btn btn-info" type="submit">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9 p-b-80">
                    <div class="p-r-45 p-r-0-lg">
                        <div class="flex-w flex-sb-m p-b-52">

                            <div class="flex-w flex-c-m m-tb-10">
                                <div
                                    class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                                    <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                                    <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                                    Filter
                                </div>
                            </div>

                            <!-- Filter -->
                            <div class="dis-none panel-filter w-full p-t-10">
                                <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                                    <div class="filter-col1 p-r-15 p-b-27">
                                        <div class="mtext-102 cl2 p-b-15">
                                            Sort By
                                        </div>

                                        <ul>

                                            <li class="p-b-6">
                                                <a href="{{ route('shop.product_asc', ['id'=>$shoper->snapshot()->id()]) }}"
                                                    class="filter-link stext-106 trans-04">
                                                    Price: Low to High
                                                </a>
                                            </li>

                                            <li class="p-b-6">
                                                <a href="{{ route('shop.product_desc', ['id'=>$shoper->snapshot()->id()]) }}"
                                                    class="filter-link stext-106 trans-04">
                                                    Price: High to Low
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row isotope-grid" id="product_list">
                            @foreach ($products as $product)
                                @php
                                    try {
                                        $url = '';
                                        $productRef = app('firebase.firestore')
                                            ->database()
                                            ->collection('product')
                                            ->document($product->id());
                                        $imageCollectionRef = $productRef->collection('image');
                                        $imgdocs = $imageCollectionRef->documents();
                                        $optionCollectionRef = $productRef->collection('option');
                                        $optiondocs = $optionCollectionRef->documents();

                                        if (!$imgdocs->isEmpty()) {
                                            foreach ($imgdocs as $imgdoc) {
                                                $url = $imgdoc->data()['url'];
                                                break;
                                            }
                                        } else {
                                            $url = 'https://sieuthikhan.com/images/thumbs/default-image_450.png';
                                        }
                                    } catch (\Exception $e) {
                                        $url = 'https://sieuthikhan.com/images/thumbs/default-image_450.png';
                                    }
                                @endphp
                                <div
                                    class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $product->data()['idCategory'] }}">
                                    <!-- Block2 -->
                                    <div class="block2">
                                        <div class="block2-pic hov-img0 label-new" data-label="New">
                                            <img src="{{ $url }}"
                                                style="width: 250px;height: 350px;object-fit: cover;" alt="IMG-PRODUCT">
                                            <a href="#"
                                                class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                                Quick View
                                            </a>
                                        </div>

                                        <div class="block2-txt flex-w flex-t p-t-14">
                                            <div class="block2-txt-child1 flex-col-l ">
                                                <a href="{{ url('product-detail', $product->id()) }}"
                                                    class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                    {{ $product->data()['name'] }}
                                                </a>

                                                <span class="stext-105 cl3">
                                                    {{ $product->data()['price'] }}
                                                </span>

                                                <!-- <span class="stext-105 cl3">
                                                                            
                                                                                                                    </span> -->
                                            </div>

                                            <div class="block2-txt-child2 flex-r p-t-3">
                                                <a href="#"
                                                    class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                                    <img class="icon-heart1 dis-block trans-04"
                                                        src="{{ asset('frontend/images/icons/icon-heart-01.png') }}"
                                                        alt="ICON">
                                                    <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                                        src="{{ asset('frontend/images/icons/icon-heart-02.png') }}"
                                                        alt="ICON">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
