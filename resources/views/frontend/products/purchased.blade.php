@extends('frontend.layout')

@section('title', 'Purchased')

@section('content')

    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <a href="" class="stext-109 cl8 hov-cl1 trans-04">
                Purchased
            </a>
        </div>
    </div>

    <!-- Shoping Cart -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 col-xl-12 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">ID Order</th>
                                <th class="column-2">Products</th>
                                <th class="column-4">Total</th>
                                <th class="column-5">Status</th>
                            </tr>

                            @foreach ($orders as $order)
                                @php
                                    $oderRef = app('firebase.firestore')
                                        ->database()
                                        ->collection('order')
                                        ->document($order->id());
                                    $optionOrders = $oderRef->collection('option')->documents();
                                @endphp
                                <tr class="table_row">
                                    <td class="column-1">{{ $order->id() }}</td>
                                    <td class="column-2">
                                        @foreach ($optionOrders as $optionOrder)
                                            @php
                                                $product = app('firebase.firestore')
                                                    ->database()
                                                    ->collection('product')
                                                    ->document($optionOrder->data()['idProduct']);
                                                $option = $product
                                                    ->collection('option')
                                                    ->document($optionOrder->data()['idOption'])
                                                    ->snapshot();
                                            @endphp
                                            <div class="flex-w flex-m m-b-30">
                                                <div class="wrap-pic-w size-w-50 bo-all-1 bocl12 m-r-30">
                                                    <img src="{{ $option->data()['image'] }}" width="75px" height="75px"
                                                        alt="PRODUCT">
                                                </div>

                                                <div class="size-w-75 flex-w flex-sb-m">
                                                    <div class="size-w-75 flex-w flex-col-m">
                                                        <span class="mtext-106 cl2 p-b-7">
                                                            {{ $product->snapshot()->data()['name'] }}
                                                            {{ $option->data()['name'] }}
                                                        </span>

                                                        <span class="mtext-106 cl2">
                                                            {{ $option->data()['quantity'] }} x
                                                            {{ number_format($option->data()['price']) }} VNĐ
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="column-4">{{ number_format($order->data()['totalByShop']) }} VNĐ</td>
                                    <td class="column-5 text-success">{{ $order->data()['status'] }}</td>
                                </tr>
                            @endforeach
                            @if ($orders->isEmpty())
                                <tr class="table_row">
                                    <td colspan="5" class="text-center p-t-30 p-b-30">You have no order product
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
