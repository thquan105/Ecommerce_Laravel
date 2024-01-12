@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dữ liệu Sản phẩm</h3>
                            <a href="{{ route('seller.products.create') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-plus"></i> Thêm </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ảnh</th>
                                            <th>Tên</th>
                                            <th>Ngành hàng</th>
                                            <th>Danh mục</th>
                                            <th>Giá</th>
                                            <th>Đã bán</th>
                                            <th>Kho hàng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                @php
                                                    $firestore = app('firebase.firestore')->database();
                                                    $productRef = $firestore->collection('product')->document($product->id());
                                                    $imgProduct = $productRef->collection('image')->documents();
                                                    $categoryShop = $firestore
                                                        ->collection('user')
                                                        ->document($product->data()['idShop'])
                                                        ->collection('categoryShop')
                                                        ->document($product->data()['idCategoryShop'])
                                                        ->snapshot();
                                                    $attributes = $productRef->collection('option')->documents();
                                                    $minPrice = PHP_INT_MAX;
                                                    $maxPrice = 0;
                                                    $totalQuantity = 0;
                                                    foreach ($attributes as $attribute) {
                                                        if ($minPrice >= $attribute->data()['price']) {
                                                            $minPrice = $attribute->data()['price'];
                                                        }
                                                        if ($maxPrice <= $attribute->data()['price']) {
                                                            $maxPrice = $attribute->data()['price'];
                                                        }
                                                        $totalQuantity += $attribute->data()['quantity'];
                                                    }
                                                    $category = $firestore->collection('category')->document($product->data()['idCategory']);
                                                    $subcategory = $category->collection('subcategory')->document($product->data()['idSubCategory']);
                                                @endphp
                                                <td>
                                                    @if (isset($imgProduct))
                                                        @foreach ($imgProduct as $item)
                                                            <a href="{{ $item->data()['url'] }}" target="_blank">
                                                                <img src="{{ $item->data()['url'] }}" width="45px"
                                                                    height="45px" alt="">
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="badge badge-warning">No image</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->data()['name'] ?? 'None' }}</td>
                                                <td>{{ $subcategory->snapshot()->data()['name'] ?? 'None' }}</td>
                                                <td>{{ $categoryShop->data()['name'] ?? 'None' }}</td>
                                                @if ($attributes->size() == 1)
                                                    <td>{{ number_format($minPrice) }}</td>
                                                @else
                                                    <td>{{ number_format($minPrice) }} - {{ number_format($maxPrice) }} VND</td>
                                                @endif
                                                <td>{{ $product->data()['sold'] ?? 0 }}</td>
                                                <td>{{ $totalQuantity }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('seller.products.edit', $product->id()) }}"
                                                            class="btn btn-sm btn-primary mr-1">
                                                            <i class="fa fa-edit">{{ __(' Sửa') }}</i>
                                                        </a>
                                                        <form onclick="return confirm('Chắc chắn xóa ?')"
                                                            action="{{ route('seller.products.destroy', $product->id()) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" type="submit"><i
                                                                    class="fa fa-trash">{{ __(' Xóa') }}</i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Trống !</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('style-alt')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
@endpush

@push('script-alt')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
        $("#data-table").DataTable();
    </script>
@endpush
