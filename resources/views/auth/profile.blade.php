@extends('frontend.layout')

@section('title', 'Profile')

@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Profile
            </span>
        </div>
    </div>

    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" src="{{ $user->data()['photo'] ?? '' }}"
                            alt="">
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        <!-- Profile picture upload button-->
                        <button class="btn btn-primary" type="button">Upload new image</button>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update', $uid) }}" method="post">
                            @csrf
                            @method('put')
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="name">Username (how your name will appear to other
                                    users on the site)</label>
                                <input class="form-control" id="name" type="text" name="name"
                                    value="{{ $user->data()['name'] ?? 'User Name' }}">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="first_name">First Name</label>
                                    <input class="form-control" type="text" name="first_name" id="first_name"
                                        value="{{ $user->data()['name'] ?? 'First Name' }}">
                                    @error('first_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="last_name">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name"
                                        value="{{ $user->data()['name'] ?? 'Last Name' }}">
                                    @error('last_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Form Group (email address)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address</label>
                                <input class="form-control" type="text" name="email" id="email"
                                    value="{{ $user->data()['email'] ?? 'Example@email.com' }}">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Form Row-->
                            <!-- Form Row        -->
                            {{-- <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1">Province <span class="required">*</span></label>
                                    <select class="form-control" name="province_id" id="province-id"
                                        value="{{ auth()->user()->province_id }}">
                                        <option value="">Select Province</option>
                                        @foreach ($provinces as $province => $pro)
                                            <option {{ auth()->user()->province_id == $province ? 'selected' : null }}
                                                value="{{ $province }}">{{ $pro }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                    <label class="small mb-1">City <span class="required">*</span></label>
                                    <select class="form-control" name="city_id" id="city-id"
                                        value="{{ auth()->user()->city_id }}">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city => $ty)
                                            <option {{ auth()->user()->city_id == $city ? 'selected' : null }}
                                                value="{{ $city }}">{{ $ty }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <!-- Form Group (phone number)-->
                                    <label class="small mb-1" for="phone">Phone <span class="required">*</span></label>
                                    <input class="form-control" type="phone" name="phone" id="phone"
                                        value="{{ $user->data()['phone'] ?? 'Phone' }}">
                                    @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="postcode">Postcode / Zip <span
                                            class="required">*</span></label>
                                    <input class="form-control" type="number" name="postcode" id="postcode"
                                        value="{{ $user->data()['postcode'] ?? 'Postcode' }}">
                                    @error('postcode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <button class="btn btn-primary" type="submit">Save changes</button>
                            <a class="btn btn-link" href="{{ route('password.change') }}">
                                <button class="btn btn-info" type="button">Change Password</button>
                            </a>
                            @if (!$user->data()['seller'])
                                <a class="btn btn-link" href="/home/iamseller">
                                    <button class="btn btn-success" type="button">Make Seller</button>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
