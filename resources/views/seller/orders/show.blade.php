@extends('layouts.seller.app')

@section('content')
    <div class="container">
        <div class="content">
            <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
                <div class="d-flex justify-content-between">
                    <h2 class="text-dark font-weight-medium">Order ID #{{ $order->id() }}</h2>
                    <div class="btn-group">
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-success">
                            Go Back</a>
                    </div>
                </div>
                @php
                    $total = 0;
                    $user = app('firebase.firestore')
                        ->database()
                        ->collection('user')
                        ->document($order->data()['idUser'])
                        ->snapshot();
                    $ship = app('firebase.firestore')
                        ->database()
                        ->collection('shippingUnit')
                        ->document($order->data()['idShippingUnit'])
                        ->snapshot();
                @endphp
                <div class="row pt-5">
                    <div class="col-xl-5 col-lg-5">
                        <p class="text-dark mb-2" style="font-weight: normal; font-size:24px; text-transform: uppercase;">
                            Billing Address</p>
                        <address>
                            Customer: {{ $user->data()['name'] ?? '' }}
                            <br> Address: {{ $user->data()['address'] ?? 'QN' }}
                            <br> Email: {{ $user->data()['email'] ?? '' }}
                            <br> Phone: {{ $user->data()['mobileNo'] ?? '+84000000000' }}
                            <br> Postcode: {{ $user->data()['postCode'] ?? '111' }}
                        </address>
                    </div>
                    <div class="col-xl-5 col-lg-5">
                        <p class="text-dark mb-2" style="font-weight: normal; font-size:24px; text-transform: uppercase;">
                            Details</p>
                        <address>
                            ID: <span class="text-dark">#{{ $order->id() }}</span>
                            <br> DATE: <span>{{ $order->data()['atCreate'] }}</span>
                            <br>
                            <br> Status: {{ $order->data()['status'] }}
                            <br> Payment Status: {{ $order->data()['status'] }}
                        </address>
                    </div>
                </div>
                <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderItems as $item)
                            <tr>
                                @php
                                    $product = app('firebase.firestore')
                                        ->database()
                                        ->collection('product')
                                        ->document($item->data()['idProduct']);
                                    $option = $product
                                        ->collection('option')
                                        ->document($item->data()['idOption'])
                                        ->snapshot();
                                    $total += $option->data()['price'] * $item->data()['quantity'];
                                @endphp
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->snapshot()->data()['name'] }}</td>
                                <td>{{ $option->data()['name'] ?? '' }}</td>
                                <td>{{ $item->data()['quantity'] }}</td>
                                <td>{{ number_format($option->data()['price']) }} VNĐ</td>
                                <td>{{ number_format($option->data()['price'] * $item->data()['quantity']) }} VNĐ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Order item not found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="row justify-content-end">
                    <div class="col-lg-5 col-xl-4 col-xl-3 ml-sm-auto">
                        <ul class="list-unstyled mt-4">
                            <li class="mid pb-3 text-dark">Subtotal
                                <span class="d-inline-block float-right text-default">{{ number_format($total) }} VNĐ</span>
                            </li>
                            <li class="mid pb-3 text-dark">Shipping Name:
                                <span class="d-inline-block float-right text-default">{{ $ship->data()['name'] }} VNĐ</span>
                            </li>
                            <li class="mid pb-3 text-dark">Shipping Cost
                                <span class="d-inline-block float-right text-default">{{ number_format($ship->data()['price']) }} VNĐ</span>
                            </li>
                            <li class="pb-3 text-dark">Total
                                <span class="d-inline-block float-right">{{ number_format($order->data()['totalByShop']) }} VNĐ</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
