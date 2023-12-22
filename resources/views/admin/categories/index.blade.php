@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">CATEGORY</h3>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-plus"></i> Thêm </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Danh mục</th>
                                            <th>Ảnh</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $category)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $category->data()['name'] }}</td>
                                                <td>
                                                    @if (isset($category->data()['photo']))
                                                        <a href="{{ $category->data()['photo'] }}" target="_blank">
                                                            <img src="{{ $category->data()['photo'] }}" width="45px"
                                                                height="45px" alt="">
                                                        </a>
                                                    @else
                                                        <span class="badge badge-warning">No image</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.subcategories.index', $category->id()) }}"
                                                            class="btn btn-sm btn-success mr-4">
                                                            <i class="fa fa-eye">{{ __(' Xem Subcategory') }}</i>
                                                        </a>
                                                        <a href="{{ route('admin.categories.edit', $category->id()) }}"
                                                            class="btn btn-sm btn-primary mr-4">
                                                            <i class="fa fa-edit">{{ __(' Sửa') }}</i>
                                                        </a>
                                                        <form onclick="return confirm('Chắc chắn xóa ?')"
                                                            action="{{ route('admin.categories.destroy', $category->id()) }}"
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
                                                <td colspan="4" class="text-center">Trống !</td>
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
