@extends('frontend.layout')

@section('title', 'Search')

@section('content')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-40" style="background-image: url('images/bg-02.jpg');">
        <h2 class="ltext-105 cl0 txt-center text-dark">
            Search results
        </h2>
        <h4 class="ltext-30 cl0 txt-center text-secondary">
            Products found: {{ $products->size() }}
        </h4>
    </section>
    <h4 class="ltext-20 cl0 txt-center text-dark">
        Search results for "{{ $keyword }}"
    </h4>
    <hr>
    <!-- Product -->
    <!-- Content page -->
    <section class="bg0 p-t-32 p-b-60">
        <div class="container">
            <div class="col-md-12 col-lg-12 p-b-80">
                <div class="p-r-45 p-r-0-lg">

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
                                        <img src="{{ $url }}" style="width: 250px;height: 350px;object-fit: cover;"
                                            alt="IMG-PRODUCT">
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
                                            <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
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
    </section>

@endsection
