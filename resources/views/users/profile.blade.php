@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Profile {{ auth()->user()->name }}
            </div>
            <div class="card-body">
                <form id="ModalForm" action="{{ route('user.profile') }}" method="post">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Cabang</label>
                        <div class="col-sm-10"><div class="form-control">@if(!is_null($data->cabang)) {{ $data->cabang->cab_name }} @endif</div> </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pengguna</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email / Username</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" value="{{ $data->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kata Sandi</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input placeholder="biarkan kosong jika tidak ingin dirubah" type="password" class="form-control" name="kata_sandi" id="kata_sandi">
                                <div class="input-group-append">
                                    <button onclick="$('#kata_sandi').attr('type')=='text' ? $('#kata_sandi').attr('type','password') : $('#kata_sandi').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label">Ulangi Kata Sandi</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input placeholder="biarkan kosong jika tidak ingin dirubah" type="password" class="form-control" name="ulangi_kata_sandi" id="ulangi_kata_sandi">
                                <div class="input-group-append">
                                    <button onclick="$('#ulangi_kata_sandi').attr('type')=='text' ? $('#ulangi_kata_sandi').attr('type','password') : $('#ulangi_kata_sandi').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Level Pengguna</label>
                        <div class="col-sm-4">
                            <div class="form-control">{{ $data->level->lvl_name }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-submit btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

    </script>

@endsection
