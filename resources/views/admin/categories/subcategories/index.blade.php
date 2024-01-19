@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-info shadow-sm float-left mr-5"> <i
                                    class="fa fa-arrow-left"></i> Back</a>
                            <h3 class="card-title">SUB CATEGORY</h3>
                            <a href="{{ route('admin.subcategories.create', $Cate->id()) }}" class="btn btn-success shadow-sm float-right">
                                <i class="fa fa-plus"></i> Thêm </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Danh mục</th>
                                            <th>Danh mục cha</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subcategories as $subcategory)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $subcategory->data()['name'] }}</td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $Cate->data()['name'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.subcategories.edit', ['category' =>$Cate->id(), 'subcategory'=>$subcategory->id()]) }}"
                                                            class="btn btn-sm btn-primary mr-4">
                                                            <i class="fa fa-edit">{{ __('Sửa') }}</i>
                                                        </a>
                                                        <form onclick="return confirm('Chắc chắn xóa ?')"
                                                            action="{{ route('admin.subcategories.destroy', ['category' =>$Cate->id(), 'subcategory'=>$subcategory->id()]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" type="submit"><i
                                                                    class="fa fa-trash">{{ __('Xóa') }}</i></button>
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
