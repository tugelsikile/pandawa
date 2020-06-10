@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Data Cabang
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="dataTable" style="width: 100%">
                    <thead>
                    <tr>
                        <th>Nama Cabang</th>
                        <th class="min-desktop">Jenis Mitra</th>
                        <th class="min-desktop">Share</th>
                        <th class="min-desktop">Jml Pelanggan</th>
                        <th class="min-desktop">Tagihan Bulan Ini</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar"B><"row"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "responsive"    : true,
            "deferRender"   : true,
            "fixedHeader"   : true,
            "searchDelay"   : 2000,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-cabang/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            buttons         : [
                @if($privs->C_opt == 1)
                {
                    className : 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-plus"></i> Tambah Cabang',
                    action : function (e,dt,node,config) {
                        show_modal({'href':'{{ url('admin-cabang/create') }}','title':'Tambah Cabang'});
                    }
                }
                @endif
            ],
            "columns"   : [
                { "data" : "cab_name", render : function (a,b,c) {
                        var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="dropdown show float-right">' +
                                '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    '<a class="dropdown-item" href="{{ url('admin-cabang/performa-tagihan?id=') }}'+c.cab_id+'"><i class="fa fa-line-chart"></i> Performa Tagihan</a>' +
                                @if($privs->U_opt == 1)
                                    '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Cabang" href="{{ url('admin-cabang/update?id=') }}'+c.cab_id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                @endif
                                @if($privs->D_opt == 1)
                                    '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Cabang" data-id="'+c.cab_id+'" onclick="delete_data(this);return false" href="{{ url('admin-cabang/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                @endif
                                '</div>' +
                            '</div>' +
                        @endif
                        '';
                        return c.cab_name+html;
                    }
                },
                { "data" : "mitra", "width" : "100px", "className" : "text-center", render : function (a,b,c) {
                        return c.mitra == 1 ? '<span class="label label-success">Mitra</span>' : '<span class="label label-default">Cabang</span>';
                    }
                },
                { "data" : "share_percent", "width" : "50px", "className" : "text-center", render : function (a,b,c) {
                        return c.share_percent+'%';
                    }
                },
                { "data" : "cab_name", "width" : "70px", "className" : "text-center", "orderable" : false, render : function (a,b,c) {
                        return c.customer.length;
                    }
                },
                { "data" : "cab_name", "orderable" : false, "width" : "150px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.invoice;
                    }
                }
            ]
        });
    </script>

@endsection
