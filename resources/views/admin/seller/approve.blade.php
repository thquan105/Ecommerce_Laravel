@extends('layouts.admin.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Seller approval') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="alert alert-info">
                        {{-- Data Seller --}}
                    </div>

                    <div class="card">
                        <div class="card-body p-0">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Shop Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Post Code/Zip</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sellers as $seller)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $seller->data()['name'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['firstName'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['lastName'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['shopName'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['email'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['mobileNo'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['andress'] ?? 'None' }}</td>
                                            <td>{{ $seller->data()['postCode'] ?? 'None' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <form class="mr-4" onclick="return confirm('Chắc chắn Duyệt ?')"
                                                        action="{{ route('admin.sellers.accept', $seller->id()) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('GET')
                                                        <button class="btn btn-sm btn-success" type="submit"><i
                                                                class="fa fa-unlock">{{ __(' Duyệt') }}</i></button>
                                                    </form>
                                                    <form onclick="return confirm('Chắc chắn Từ chối ?')"
                                                        action="{{ route('admin.sellers.deny', $seller->id()) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('GET')
                                                        <button class="btn btn-sm btn-danger" type="submit"><i
                                                                class="fa fa-lock">{{ __(' Từ Chối') }}</i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
