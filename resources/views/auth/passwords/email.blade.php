@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="container jumbotron">
        <div class="login-logo">
            <a href="{{ route('home') }}"><b>Book</b>SHOP</a>
        </div>
        <div class="card">
            <div class="card-header">{{ __('Reset Password') }}</div>

            <div class="card-body">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissible fade show">
                        {{ Session::get('message') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ Session::get('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ $error }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endforeach
                @endif

                <form method="POST" action="{{ route('reset.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">Login</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                </p>
            </div>
        </div>
    </div>
@endsection
