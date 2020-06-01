@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-15">
                    <div class="pull-right">
                        @if($privs->C_opt == 1)
                            <a title="Tambah Cabang" href="{{ url('admin-cabang/create') }}" class="btn btn-primary" onclick="show_modal(this);return false"><i class="fa fa-plus"></i> Cabang Baru</a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <table class="table table-bordered" id="dataTable" style="width: 100%">
                    <thead>
                    <tr>
                        <th>Nama Cabang</th>
                        <th>Jenis Mitra</th>
                        <th>Share</th>
                        <th>Jml Pelanggan</th>
                        <th>Tagihan Bulan Ini</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var table = $('#dataTable').dataTable({
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-cabang/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            "columns"   : [
                { "data" : "cab_name", render : function (a,b,c) {
                        var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="btn-group btn-group-xs pull-right">\n' +
                            '  <button type="button" class="btn btn-default">Action</button>\n' +
                            '  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                            '    <span class="caret"></span>\n' +
                            '    <span class="sr-only">Toggle Dropdown</span>\n' +
                            '  </button>\n' +
                            '  <ul class="dropdown-menu">\n' +
                                @if($privs->U_opt == 1)
                            '    <li><a onclick="show_modal(this);return false" title="Rubah Data Cabang" href="{{ url('admin-cabang/update?id=') }}'+c.cab_id+'"><i class="fa fa-pencil"></i> Rubah Data</a></li>\n' +
                                @endif
                                @if($privs->D_opt == 1)
                            '    <li><a data-token="{{ csrf_token() }}" title="Hapus Data Cabang" data-id="'+c.cab_id+'" onclick="delete_data(this);return false" href="{{ url('admin-cabang/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a></li>\n' +
                                @endif
                            '  </ul>\n' +
                            '</div>\n' +
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
                { "data" : "customer_count", "width" : "70px", "className" : "text-center", "orderable" : false, render : function (a,b,c) {
                        return c.customer.length;
                    }
                },
                { "data" : "invoice", "orderable" : false, "width" : "150px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.invoice;
                    }
                }
            ]
        });
    </script>

@endsection
