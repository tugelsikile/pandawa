@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Pengeluaran Rutin</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Jenis Pengeluaran</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Jumlah</th>
                            <th class="min-mobile">Aktif</th>
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
            "order"         : [[ 1, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-kas/pengeluaran-rutin') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            buttons         : [
                {
                    className : 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-plus"></i> Tambah Pengeluaran Rutin',
                    action : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ route('admin-kas.create-pengeluaran-rutin') }}','title':'Tambah Pengeluaran Rutin'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                }
            ],
            "columns"   : [
                { "data" : "deskripsi", render : function (a,b,c,d) {
                        var html = '' +
                            @if($privs->U_opt == 1 || $privs->D_opt == 1)
                                '<div class="dropdown show float-right">' +
                                    '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    @if($privs->U_opt == 1)
                                        '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Pengeluaran Rutin" href="{{ url('admin-kas/update-pengeluaran-rutin?id=') }}'+c.id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                    @endif
                                    @if($privs->D_opt == 1)
                                        '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Pengeluaran Rutin" data-id="'+c.id+'" onclick="delete_data(this);return false" href="{{ url('admin-kas/delete-pengeluaran-rutin') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                    @endif
                                    '</div>' +
                                '</div>' +
                            @endif
                            '';
                        return c.deskripsi+html;
                    }
                },
                { "data" : "start_date", "width" : "120px" },
                { "data" : "end_date", "width" : "120px" },
                { "data" : "ammount", "width" : "120px", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ) },
                { "data" : "is_active", "width" : "80px" },
            ]
        });
    </script>

@endsection
