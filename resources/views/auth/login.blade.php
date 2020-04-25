@extends('layouts.guest')

@section('content')
<div class="container container-fluid">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <form method="POST" class="form form-horizontal" action="{{ route('login') }}">
            @csrf
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Login') }}</div>
                <div class="panel-body">
                    <div class="form-group @error('email') has-error @enderror">
                        <label for="email" class="col-sm-4 control-label">E-mail / Username <i class="fa fa-envelope"></i> </label>
                        <div class="col-sm-4">
                            <input type="text" required name="email" id="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group @error('password') has-error @enderror">
                        <label for="password" class="col-sm-4 control-label">Password <i class="fa fa-lock"></i> </label>
                        <div class="col-sm-4">
                            <input type="password" required name="password" id="password" class="form-control" value="{{ old('password') }}">
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <label class="control-label" for="remember">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                Ingat saya
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Login</button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Lupa Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-1"></div>
</div>
@endsection
