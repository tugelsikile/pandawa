@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Halaman</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Nama Level Pengguna</th>
                            <th class="min-desktop">Jml Pengguna</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar"B><"row clearfix"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-access/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            buttons         : [
                @if($privs->C_opt == 1)
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Hak Akses',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-access/create') }}','title':'Tambah Hak Akses'});
                        @else
                        showError('Forbidden Action');
                        @endif
                    }
                },
                @endif
                @if($privs->D_opt == 1)
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-pencil"></i> Data Halaman dan Fungsi',
                    action      : function (e,dt,node,config) {
                        window.location.href = '{{ url('admin-access/halaman-dan-fungsi') }}'
                    }
                }
                @endif
            ],
            "columns"   : [
                { "data" : "lvl_name", render : function (a,b,c) {
                        var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="dropdown show float-right">' +
                                '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                            @if($privs->U_opt == 1)
                                '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Level Pengguna" href="{{ url('admin-access/update?id=') }}'+c.lvl_id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                            @endif
                            @if($privs->D_opt == 1)
                                '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Level Pengguna" data-id="'+c.lvl_id+'" onclick="delete_data(this);return false" href="{{ url('admin-access/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                            @endif
                                '</div>' +
                            '</div>' +
                        @endif
                        '';
                        return c.lvl_name + html;
                    }
                },
                { "data" : "lvl_id", "width" : "100px", "orderable" : false, render : function (a,b,c) {
                        return c.users.length;
                    }
                }
            ]
        });
    </script>

@endsection
