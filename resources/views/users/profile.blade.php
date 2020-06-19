@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Profile {{ auth()->user()->name }}
            </div>
            <div class="card-body">
                <form id="userForm">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Cabang</label>
                        <div class="col-sm-10"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

    </script>

@endsection
