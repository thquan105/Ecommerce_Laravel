@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chỉnh sửa dịch vụ ship</h3>
                            <a href="{{ route('seller.categories.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Trở lại</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.ship.update', $shipes->id()) }}">
                                @csrf
                                @method('put')  
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Tên dịch vụ</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $shipes->data()['name']) }}" id="name">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="price" class="col-sm-2 col-form-label">Giá tiền</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="price"
                                            value="{{ old('price', $shipes->data()['price']) }}" id="price">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="time" class="col-sm-2 col-form-label">Thời gian</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="time"
                                            value="{{ old('time', $shipes->data()['deliveryTime']) }}" id="time">
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



