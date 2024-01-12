@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0 font-weight-bold text-primary">SHIP</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Tên người mua</th>
                                            <th>Dịch vụ ship</th>
                                            <th>Ngày đặt hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Tình trạng</th>
                                            <th class="text-center" style="width: 30px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            @php
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
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $order->id() }}
                                                </td>
                                                <td>
                                                    {{ $user->data()['name'] }} <br />
                                                    {{ $user->data()['address'] ?? 'QN' }}
                                                </td>
                                                <td>{{ $ship->data()['name'] }}</td>
                                                <td>{{ $order->data()['atCreate'] }}</td>
                                                <td>
                                                    @if ($order->data()['status'] == 'đang chờ xử lý')
                                                        <span
                                                            class="badge badge-info">Đang chờ xử lý</span>
                                                    @elseIf($order->data()['status'] == 'đang vận chuyển')
                                                        <span
                                                            class="badge badge-success">Đang vận chuyển</span>
                                                    @elseIf($order->data()['status'] == 'đã giao hàng')
                                                        <span
                                                            class="badge badge-primary">Đã giao hàng</span>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">Đã hủy</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($order->data()['totalByShop']) }} VNĐ</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('seller.orders.show', $order->id()) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        @if ($order->data()['status'] == 'đang chờ xử lý')
                                                            <a href="{{ route('seller.orders.edit', $order->id()) }}"
                                                                onclick="return confirm('Are you sure !')"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                            <form onclick="return confirm('Are you sure !')"
                                                                action="{{ route('seller.orders.destroy', $order->id()) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger" type="submit"><i
                                                                        class="fa fa-window-close"></i></button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="12">No products found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
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
