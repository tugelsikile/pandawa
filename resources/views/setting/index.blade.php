@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Setting
                <div class="dropdown float-right dropleft">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ url('setting/data-perusahaan') }}">Data Perusahaan</a>
                        <a class="dropdown-item" href="{{ url('setting/data-bank') }}">Data Akun Bank</a>
                        <a class="dropdown-item" href="{{ url('setting/template-invoice') }}">Template Nomor Invoice</a>
                        <a class="dropdown-item" href="{{ url('setting/template-email') }}">Template Email</a>
                        <a class="dropdown-item" href="{{ url('setting/email') }}">Setting Email</a>
                        <a class="dropdown-item" href="{{ url('setting/application-logs') }}">Application Logs</a>
                    </div>
                </div>
            </div>
            <div class="card-body"></div>
        </div>
    </div>
@endsection
