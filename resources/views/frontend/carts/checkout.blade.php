@extends('frontend.layout')

@section('title', 'Checkout')

@section('content')

<!-- breadcrumb -->
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <a href="{{ route('carts.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
            Shoping Cart
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">
            Checkout
        </span>
    </div>
</div>

<!-- Checkout Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div class="mb-4">
                <h4 class="mtext-109 cl2 p-b-30">
                    Billing Address
                </h4>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>First Name</label>
                        <input class="form-control" type="text" value="{{$user->data()['firstName'] ?? ''}}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" value="{{$user->data()['lastName'] ?? ''}}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>E-mail</label>
                        <input class="form-control" type="text" value="{{$user->data()['email'] ?? ''}}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mobile No</label>
                        <input class="form-control" type="text" value="{{$user->data()['mobileNo'] ?? ''}}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Address</label>
                        <input class="form-control" type="text" value="{{$user->data()['address'] ?? ''}}">
                    </div>

                </div>
            </div>
        </div>

        <div class=" col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg3 border-0">
                    <h4 class="mtext-109 text-white cl2">
                        Order Total
                    </h4>
                </div>


                <div class="card-body">
                    <h5 class="font-weight-medium mb-3">Products</h5>
                    @foreach($idproducts as $idproduct)
                    @php
                    $prodRef = app('firebase.firestore')->database()->collection('product');
                    $product= $prodRef->document($idproduct)->snapshot();
                    $optionRef = $prodRef->document($idproduct)->collection('option');
                    @endphp
                    <div class="d-flex justify-content-between">
                        <p>{{$product->data()['name']}}</p>
                        @foreach($idoptions as $idoption)
                        @php
                        $option = $optionRef->document($idoption)->snapshot();
                        @endphp
                        <p>{{ $option->data()['name'] ?? ''}}</p>
                        <p>{{ $option->data()['price'] ?? ''}}</p>
                        @endforeach
                    </div>
                    @endforeach
                    <hr class="mt-0">
                    @php
                    $shipping = app('firebase.firestore')->database()
                    ->collection('shippingUnit')->document($idShippingUnit)->snapshot();
                    @endphp
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">{{$shipping->data()['name']}}</h6>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold">{{ $totalByShop }} vnd</h5>
                    </div>
                </div>
            </div>
            <div class="card border-secondary mb-5">
                <div class="card-header bg3 border-0">
                    <h4 class="mtext-109 text-white cl2">
                        Payment
                    </h4>
                </div>
                <div class="card-body">
                    <div class="form-group ml-4">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="payment" id="paypal" checked>
                            <label class="form-check-label" for="paypal">Paypal</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check ml-4">
                            <input type="radio" class="form-check-input" name="payment" id="directcheck">
                            <label class="form-check-label" for="directcheck">Direct Check</label>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-check ml-4">
                            <input type="radio" class="form-check-input" name="payment" id="banktransfer">
                            <label class="form-check-label" for="banktransfer">Bank Transfer</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <button class="flex-c-m stext-101 cl0 size-116 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Checkout End -->

@endsection