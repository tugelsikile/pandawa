@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Customer</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Kode Barang</th>
                            <th class="min-mobile">Nama Barang</th>
                            <th class="min-desktop">Harga Beli</th>
                            <th class="min-desktop">Tgl Beli</th>
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
            "order"         : [[ 1, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('barang/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            buttons         : [
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Barang',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('barang/create') }}','title':'Tambah Barang'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                }
            ],
            "columns"   : [
                { "data" : "kode", "width" : "150px" },
                { "data" : "nama_barang", render : function (a,b,c) {
                    var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="dropdown show float-right">' +
                                '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    @if($privs->U_opt == 1)
                                        '<a onclick="show_modal(this);return false" href="{{ url('barang/update') }}?id='+c.br_id+'" title="Rubah Data Barang" class="dropdown-item">Rubah Data</a>' +
                                    @endif
                                    @if($privs->D_opt == 1)
                                        '<a data-id="'+c.br_id+'" onclick="delete_data(this);return false" href="{{ url('barang/delete') }}" title="Hapus Data Barang" data-token="{{ csrf_token() }}" class="dropdown-item">Hapus Data</a>' +
                                    @endif
                                '</div>' +
                            '</div>' +
                        @endif
                    '';
                    return c.nama_barang + html;
                    }
                },
                { "data" : "price_buy", "width" : "150px", render : function (a,b,c) {
                    return c.harga_beli;
                    }
                },
                { "data" : "date_buy", "width" : "150px", render : function (a,b,c) {
                    return c.tgl_beli;
                    }
                }
            ]
        });
    </script>

@endsection
