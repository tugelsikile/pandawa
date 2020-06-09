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
                        <a onclick="settingPage(this);return false" class="dropdown-item" href="{{ url('setting/data-perusahaan') }}">Data Perusahaan</a>
                        <a onclick="settingPage(this);return false" class="dropdown-item" href="{{ url('setting/data-bank') }}">Data Akun Bank</a>
                        <a onclick="settingPage(this);return false" class="dropdown-item" href="{{ url('setting/template-invoice') }}">Template Nomor Invoice</a>
                        <a onclick="settingPage(this);return false" class="dropdown-item" href="{{ url('setting/template-email') }}">Template Email</a>
                        <a onclick="settingPage(this);return false" class="dropdown-item" href="{{ url('setting/email') }}">Setting Email</a>
                        <a target="_blank" class="dropdown-item" href="{{ url('setting/application-logs') }}">Application Logs</a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="setting-container"></div>
        </div>
    </div>
    <script>
        settingPage({'href':'{{ url('setting/data-perusahaan') }}'})
    </script>
@endsection
