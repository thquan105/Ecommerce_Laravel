@extends('frontend.layout')

@section('title', 'Wishlist')

@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                My Wishlist
            </span>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="container p-t-30">
            <div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Wishlist -->
    <div class="container p-t-30">
        <div class="row">
            <div class="col-lg-10 col-xl-12 m-lr-auto m-b-50">
                <div class="m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <thead>
                                <tr class="table_head">
                                    <th class="column-1">Image</th>
                                    <th class="column-2">Name</th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Detail</th>
                                    <th class="column-5">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($wishlists))
                                    @foreach ($wishlists as $wishlist)
                                        @php
                                            $idproduct = $wishlist->data()['product_id'];
                                            $productRef = app('firebase.firestore')
                                                ->database()
                                                ->collection('product');
                                            $product = $productRef->document($idproduct)->snapshot();
                                            $imgdocs = $productRef
                                                ->document($idproduct)
                                                ->collection('image')
                                                ->limit(1)
                                                ->documents();
                                            if (!$imgdocs->isEmpty()) {
                                                foreach ($imgdocs as $imgdoc) {
                                                    $url = $imgdoc->data()['url'];
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <tr class="table_row">
                                            <td class="column-1">
                                                @if ($imgdocs->size() > 0)
                                                    <img src="{{ $url }}" style="width:100px">
                                                @else
                                                    <span class="badge badge-danger">no image</span>
                                                @endif
                                            </td>
                                            <td class="column-2">{{ $product->data()['name'] }}</td>
                                            <td class="column-3"><span
                                                    class="amount">{{ number_format($product->data()['price']) }}
                                                    vnđ</span>
                                            </td>
                                            <td class="column-4">
                                                <a href="{{ url('product-detail', $product->id()) }}"
                                                    class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                    Chi tiết
                                                </a>
                                            </td>
                                            <td class="column-5">
                                                <div class="remove">
                                                    <form action="{{ route('wishlists.destroy', $wishlist->id()) }}"
                                                        method="post" class="delete d-inline-block">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if (!isset($wishlists) || $wishlists->isEmpty())
                                    <tr class="table_row">
                                        <td colspan="5" class="text-center p-t-30 p-b-30">You have no wishlist product
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
