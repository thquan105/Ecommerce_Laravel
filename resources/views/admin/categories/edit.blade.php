@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chỉnh sửa danh mục</h3>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Trở lại</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.categories.update', $category->id()) }}" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Tên danh mục</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $category->data()['name']) }}" id="name">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="path" class="col-sm-2 col-form-label">Hình ảnh</label>
                                    @if (isset($category->data()['photo']))
                                        <div class="col-sm-1">
                                            <a href="{{ $category->data()['photo'] }}" target="_blank">
                                                <img src="{{ $category->data()['photo'] }}" width="45px" height="45px"
                                                    alt="">
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="path" id="path">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Lưu</button>
                            </form>
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
