@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Pengguna</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th class="min-desktop">Username / Email</th>
                            <th class="min-desktop">Cabang</th>
                            <th class="min-desktop">Level</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
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
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-account/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    @if(Auth::user()->cab_id)
                        d.cab_id = '{{ Auth::user()->cab_id }}';
                    @else
                        d.cab_id = $('div.toolbar select.cab-id').val();
                    @endif
                    d.level  = $('div.toolbar select.lvl-id').val();
                }
            },
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
            "columns"   : [
                { "data" : "name", render : function (a,b,c) {
                        var html = '' +
                            @if($privs)
                                @if($privs->U_opt == 1 || $privs->D_opt == 1)
                                    '<div class="dropdown show float-right">' +
                                        '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                        '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    @if($privs->U_opt == 1)
                                            '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Pengguna" href="{{ url('admin-account/update?id=') }}'+c.id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                    @endif
                                    @if($privs->D_opt == 1)
                                            '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Pengguna" data-id="'+c.id+'" onclick="delete_data(this);return false" href="{{ url('admin-account/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                    @endif
                                        '</div>' +
                                    '</div>' +
                                @endif
                            @endif
                            '';
                        return c.name+html;
                    }
                },
                { "data" : "email", "width" : "130px", render : function (a,b,c) {
                        return c.email;
                    }
                },
                { "data" : "cab_id", "width" : "120px", render : function (a,b,c) {
                    return c.cab_id == null ? '' : c.cabang.cab_name;
                    }
                },
                { "data" : "level", "width" : "120px", render : function (a,b,c) {
                        return c.level.lvl_name;
                    }
                }
            ]
        });
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select onchange="table._fnDraw()" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                @if(strlen(Auth::user()->cab_id)==0)
                    '<option value="">=== Semua Cabang ===</option>' +
                @endif
                @if($cabangs)
                    @foreach($cabangs as $key => $cabang)
                        '<option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>' +
                    @endforeach
                @endif
                '</select>' +
                '<select onchange="table._fnDraw()" class="mb-2 lvl-id custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Level Pengguna ===</option>' +
                    @if($levels)
                        @foreach($levels as $key => $level)
                            '<option value="{{ $level->lvl_id }}">{{ $level->lvl_name }}</option>' +
                        @endforeach
                    @endif
                '</select>' +
            '</div>');
    </script>

@endsection
