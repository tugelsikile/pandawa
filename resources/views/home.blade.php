@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">asda</div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            Selamat Datang {{ auth()->user()->name }}
        </div>
    </div>
</div>
@endsection
