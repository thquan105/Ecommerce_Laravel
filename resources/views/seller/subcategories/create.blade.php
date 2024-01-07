@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tạo một danh mục</h3>
                            <a href="{{ route('seller.categories.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Trở lại</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('seller.categories.store') }}">
                                @csrf
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Tên danh mục SHOP</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" id="name">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Tạo</button>
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
