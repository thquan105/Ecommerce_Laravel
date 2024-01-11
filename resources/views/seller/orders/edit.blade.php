@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chỉnh sửa Trạng thái đơn hàng <strong>#{{ $order->id() }}</strong></h3>
                            <a href="{{ route('seller.orders.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Trở lại</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('seller.orders.update', $order->id()) }}">
                                @csrf
                                @method('put')                             
                                <div class="form-group row border-bottom pb-4">
                                    <label for="status" class="col-sm-2 col-form-label">Tình trạng đơn hàng</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status" id="status">
                                                <option {{ "đang chờ xử lý" == $order->data()['status'] ? 'selected' : null }}
                                                    value="đang chờ xử lý">đang chờ xử lý</option>

                                                <option {{ "đang vận chuyển" == $order->data()['status'] ? 'selected' : null }}
                                                    value="đang vận chuyển">đang vận chuyển</option>

                                                <option {{ "đã giao hàng" == $order->data()['status'] ? 'selected' : null }}
                                                    value="đã giao hàng">đã giao hàng</option>

                                                <option {{ "đã hủy" == $order->data()['status'] ? 'selected' : null }}
                                                    value="đã hủy">đã hủy</option>
                                        </select>
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



