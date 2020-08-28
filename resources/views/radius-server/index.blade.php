@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Pengguna Radius Server</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th class="min-desktop">Username / Email</th>
                            <th class="min-desktop">Level</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $key => $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->user_level->name}}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('.cab_id').select2();
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar"B><"row"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            buttons         : [
                @if($privs->C_opt == 1)
                {
                    className : 'btn btn-sm btn-primary @if($privs->C_opt == 0) disabled @endif',
                    text: '<i class="fa fa-plus"></i> Tambah Pengguna',
                    action : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-account/create') }}','title':'Tambah Pengguna'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                }
                @endif
            ],
        });
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select onchange="table._fnDraw()" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                @if(strlen(auth()->user()->cab_id)==0)
                    '<option value="">=== Semua Cabang ===</option>' +
                @endif
                @if($cabangs)
                    @foreach($cabangs as $key => $cabang)
                        '<option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>' +
                    @endforeach
                @endif
                '</select>' +
            '</div>');
    </script>

@endsection
