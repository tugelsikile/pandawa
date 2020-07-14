@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-2">
        <div class="card-body">
            Selamat Datang di aplikasi <strong>{{env('APP_NAME')}}</strong>.
            <br>
            Saat ini anda login sebagai <strong>{{ auth()->user()->name }}</strong> dengan hak akses <strong>{{auth()->user()->userLevelOjb->lvl_name}}</strong>.
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            asdasd
        </div>
    </div>
</div>
@endsection
