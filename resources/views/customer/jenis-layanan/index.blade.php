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
                            <th class="min-mobile">Nama Jenis Layanan</th>
                            <th class="min-desktop">Jml Pelanggan</th>
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
            "order"         : [[ 1, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-customer/jenis-layanan/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            buttons         : [
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Jenis Layanan',
                    action      : function (e,dt,node,config) {
                        show_modal({'href':'{{ url('admin-customer/jenis-layanan/create') }}','title':'Tambah Jenis Layanan'});
                    }
                }
            ],
            "columns"   : [
                { "data" : "name", render : function (a,b,c,d) {
                      var html = c.name+'<div class="dropdown show float-right">' +
                              '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                              '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                  '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Jenis Layanan" href="{{ url('admin-customer/jenis-layanan/update?id=') }}'+c.id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                  '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Customer" data-id="'+c.id+'" onclick="delete_data(this);return false" href="{{ url('admin-customer/jenis-layanan/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                              '</div>' +
                          '</div>';
                      return html;
                    }
                },
                { "data" : "count", "width" : "150px", render : function (a,b,c,d) {
                        return c.count.length;
                    }
                }
            ]
        });
    </script>

@endsection
