@extends('frontend.layout')

@section('title', 'Products Detail')

@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <a href="{{ route('products.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Men
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                {{ $productdatas->data()['name'] }}
            </span>
        </div>
    </div>

    @if (session('alert'))
        <section class='alert alert-success'>{{ session('alert') }}</section>
    @endif


    <!-- Product Detail -->
    <section class="sec-product-detail bg0 p-t-65 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
                            <div class="slick3 gallery-lb">
                                @foreach ($images as $image)
                                    <div class="item-slick3" data-thumb="{{ $image->data()['url'] }}">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="{{ $image->data()['url'] }}" alt="IMG-PRODUCT">
                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                href="{{ $image->data()['url'] }}">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                            {{ $productdatas->data()['name'] }}
                        </h4>

                        <h5 class="mtext-105 cl2 js-name-detail p-b-14">

                        </h5>

                        @php
                            $minPrice = PHP_INT_MAX;
                            $maxPrice = 0;
                            $totalQuantity = 0;
                            foreach ($options as $option) {
                                if ($minPrice >= $option->data()['price']) {
                                    $minPrice = $option->data()['price'];
                                }
                                if ($maxPrice <= $option->data()['price']) {
                                    $maxPrice = $option->data()['price'];
                                }
                                $totalQuantity += $option->data()['quantity'];
                            }
                        @endphp
                        <span class="mtext-106 cl2" style="font-size: 25px" id="spanprice">
                            {{ number_format($minPrice) }} - {{ number_format($maxPrice) }} VNĐ
                        </span>
                        <div class="p-t-33">

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="size-203 flex-c-m respon6">
                                    Option
                                </div>

                                <div class="size-204 respon6-next">
                                    <ul>
                                        @foreach ($options as $option)
                                            <li>
                                                <button class="btn btn-outline-primary mt-3" type="button"
                                                    onclick="setvalue('{{ $option->id() }}')">
                                                    <img src="{{ $option->data()['image'] }}" alt=""
                                                        style="width: 20px;">
                                                    {{ $option->data()['name'] }}
                                                    <input type="hidden" id="{{ $option->id() }}"
                                                        value="{{ $option->data()['price'] }}">
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>


                                <div class="product-size">
                                    <ul>
                                        <li>
                                            <button class="btn btn-outline-light" type="button">M</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-outline-light" type="button">L</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-outline-light" type="button">Xl</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <form id="addtocart" method="post" action="{{ route('carts.store') }}">
                                @csrf
                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-204 flex-w flex-m respon6-next">
                                        <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>

                                            <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                name="num-product" value="1">

                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>
                                        <input type="hidden" name="idproduct" value="{{ $productdatas->id() }}">
                                        <input type="hidden" name="idoption" id="option_input">
                                        <button
                                            class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail"
                                            type="submit">
                                            Add to cart
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!--  -->
                        <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                            <div class="flex-m bor9 p-r-10 m-r-11">
                                <a href="#"
                                    class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100"
                                    data-tooltip="Add to Wishlist">
                                    <i class="zmdi zmdi-favorite"></i>
                                </a>
                            </div>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Google Plus">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="card-footer bor10 m-t-50 p-t-20 p-b-20">
                    <div class="media ">
                        <img class="mr-3 rounded-circle"
                            src="{{ $shop['photo'] ?? asset('frontend/images/avatar-01.jpg') }}"
                            alt="Generic placeholder image" style="max-width:50px">
                        <div class="media-body">
                            <a href="{{ route('shop.index', $shop->id()) }}">
                                <h5 class="text-dark">Shop: {{ $shop->data()['shopName'] ?? $shop->data()['name'] }}</h5>
                            </a>
                            <small class="text-muted">Shop Address: {{ $shop->data()['address'] ?? '' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bor10 m-t-50 p-t-43 p-b-40">
                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item p-b-10">
                            <a class="nav-link active" data-toggle="tab" href="#description"
                                role="tab">Description</a>
                        </li>

                        <li class="nav-item p-b-10">
                            <a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional
                                information</a>
                        </li>

                        <li class="nav-item p-b-10">
                            <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-t-43">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="how-pos2 p-lr-15-md">
                                <p class="stext-102 cl6">
                                    {{ $productdatas->data()['description'] }}
                                </p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <div class="p-b-30 m-lr-15-sm">

                                        <div class="flex-w flex-t p-b-68">
                                            <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                <img src="{{ asset('frontend/images/avatar-01.jpg') }}" alt="AVATAR">
                                            </div>

                                            <div class="size-207">
                                                <div class="flex-w flex-sb-m p-b-17">
                                                    <span class="mtext-107 cl2 p-r-20">
                                                        Ariana Grande
                                                    </span>

                                                    <span class="fs-18 cl11">
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star-half"></i>
                                                    </span>
                                                </div>

                                                <p class="stext-102 cl6">
                                                    Quod autem in homine praestantissimum atque optimum est, id
                                                    deseruit.
                                                    Apud ceteros autem philosophos
                                                </p>
                                            </div>
                                        </div>

                                        <form class="w-full">
                                            <h5 class="mtext-108 cl2 p-b-7">
                                                Add a review
                                            </h5>

                                            <p class="stext-102 cl6">
                                                Your email address will not be published. Required fields are marked
                                                *
                                            </p>

                                            <div class="flex-w flex-m p-t-50 p-b-23">
                                                <span class="stext-102 cl3 m-r-16">
                                                    Your Rating
                                                </span>

                                                <span class="wrap-rating fs-18 cl11 pointer">
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <input class="dis-none" type="number" name="rating">
                                                </span>
                                            </div>

                                            <div class="row p-b-25">
                                                <div class="col-12 p-b-5">
                                                    <label class="stext-102 cl3" for="review">Your
                                                        review</label>
                                                    <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
                                                </div>

                                                <div class="col-sm-6 p-b-5">
                                                    <label class="stext-102 cl3" for="name">Name</label>
                                                    <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name"
                                                        type="text" name="name">
                                                </div>

                                                <div class="col-sm-6 p-b-5">
                                                    <label class="stext-102 cl3" for="email">Email</label>
                                                    <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email"
                                                        type="text" name="email">
                                                </div>
                                            </div>

                                            <button
                                                class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
                                                Submit
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
            <span class="stext-107 cl6 p-lr-25">
                SKU: JAK-01
            </span>

            <span class="stext-107 cl6 p-lr-25">
                Categories: Jacket, Men
            </span>
        </div>
    </section>

    {{-- @include('frontend.products.related') --}}

@endsection

@push('script-alt')
    <script>
        function setvalue(idoption) {
            var option_input = document.getElementById('option_input');
            option_input.value = idoption;
            var inputprice = parseInt(document.getElementById(idoption).value);
            // document.getElementById('spanprice').innerHTML = inputprice;s
            document.getElementById('spanprice').innerHTML = inputprice.toLocaleString('it-IT', {
                style: 'currency',
                currency: 'VND'
            });

        }
    </script>
@endpush
