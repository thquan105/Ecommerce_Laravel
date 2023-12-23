@extends('frontend.layout')

@section('title', 'Make Seller')

@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <a href="{{ route('profile.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Profile
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <span class="stext-109 cl4">
                Make Seller
            </span>
        </div>
    </div>

    <div class="container-xl px-4 mt-4">
        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="row justify-content-center">
            <!-- Account details card-->
            <div class="col-xl-10">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <h3 class="mb-0">Enter Seller Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.makeSeller')}}" method="post">
                            @csrf
                            @method('put')
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="name">Username</label>
                                <input class="form-control" id="name" type="text" name="name"
                                    value="{{ $user->data()['name'] ?? 'User Name' }}">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="shopName">Shop Name</label>
                                <input class="form-control" id="shopName" type="text" name="shopName"
                                    value="{{ $user->data()['shopName'] ?? '' }}">
                                @error('shopName')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="firstName">First Name</label>
                                    <input class="form-control" type="text" name="firstName" id="firstName"
                                        placeholder="First Name" value="{{ $user->data()['firstName'] ?? '' }}">
                                    @error('first_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="lastName">Last Name</label>
                                    <input class="form-control" type="text" name="lastName" id="lastName"
                                        placeholder="Last Name" value="{{ $user->data()['lastName'] ?? '' }}">
                                    @error('last_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Form Group (email address)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address
                                    <br> (Update your account's profile information and email address. When You change your
                                    email ,you need to verify your email else the
                                    account will be blocked)</label>
                                <div class="row mb-3">
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="email" id="email"
                                            value="{{ $user->data()['email'] ?? '' }}">
                                        @error('email')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        {{-- @if (app('firebase.auth')->getUser($uid)->emailVerified) --}}
                                        <label class="btn btn-success">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            Verify
                                        </label>
                                        {{-- @else
                                            <a class="btn btn-link" href="{{ route('verify') }}">
                                                <button class="btn btn-danger" type="button">
                                                    <i class="fa fa-check-circle" aria-hidden="false"></i>
                                                    No Verify
                                                </button>
                                            </a>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ $user->data()['address'] ?? '' }}</textarea>
                                @error('address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <!-- Form Group (phone number)-->
                                    <label class="small mb-1" for="phone">Phone <span
                                            class="required">*</span></label>
                                    <input class="form-control" type="phone" name="phone" id="phone"
                                        placeholder="+84 xxx xxx xxx" value="{{ $user->data()['mobileNo'] ?? '' }}">
                                    @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="postcode">Postcode / Zip <span
                                            class="required">*</span></label>
                                    <input class="form-control" type="number" name="postcode" id="postcode"
                                        placeholder="Postcode" value="{{ $user->data()['postCode'] ?? '' }}">
                                    @error('postcode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <span class="btn col justify-content-center">
                                <button class="btn btn-info btn-lg" type="submit">Submit</button>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
