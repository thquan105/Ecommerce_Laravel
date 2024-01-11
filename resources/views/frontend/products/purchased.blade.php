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
                            <th class="column-4">Total</th>
                            <th class="column-5">Status</th>
                        </tr>

                        @foreach ($orders as $order)
                            <tr class="table_row">
                                <td class="column-1">{{ $order->id() }}</td>
                                <td class="column-4">{{ $order->data()['totalByShop'] }}</td>
                                <td class="column-5 text-success">{{ $order->data()['status'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
