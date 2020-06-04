@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Produk / Layanan</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th><input type="checkbox" onclick="tableCbxAll(this)"></th>
                            <th>Nama Produk</th>
                            <th class="min-desktop">Jml Pelanggan</th>
                            <th class="min-desktop">Kapasitas</th>
                            <th class="min-desktop">Harga</th>
                            <th class="min-desktop">Pajak</th>
                            <th class="min-desktop">Harga Setelah Pajak</th>
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
                "url"   : '{{ url('admin-produk/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.cab_id = $('div.toolbar select.cab-id').val();
                }
            },
            buttons         : [
                {
                    className : 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-plus"></i> Tambah Produk',
                    action : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-produk/create') }}','title':'Tambah Produk'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                },
                {
                    className   : 'btn btn-sm btn-danger',
                    text        : '<i class="fa fa-trash-o"></i> Hapus Produk Dipilih',
                    action      : function (e, dt, node, config) {
                        @if($privs->D_opt == 1)
                            bulk_delete({'title':'Hapus Produk Terpilih','href':'{{ url('admin-produk/bulk-delete') }}','data-token':'{{csrf_token()}}'});
                        @endif
                    }
                }
            ],
            "columns"   : [
                { "data" : "pac_id", "width" : "20px", "orderable" : false, "className" : "text-center", render : function (a,b,c) {
                        return '<input type="checkbox" name="pac_id[]" value="'+c.pac_id+'">';
                    }
                },
                { "data" : "pac_name", "responsivePriority" : 1,render : function (a,b,c) {
                        var html = '' +
                            '<div class="dropdown show float-right">' +
                                '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                @if($privs->U_opt == 1)
                                    '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Produk" href="{{ url('admin-produk/update?id=') }}'+c.pac_id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                @endif
                                @if($privs->D_opt == 1)
                                    '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Produk" data-id="'+c.pac_id+'" onclick="delete_data(this);return false" href="{{ url('admin-produk/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                @endif
                                '</div>' +
                            '</div>';
                        return c.pac_name+html;
                    }
                },
                { "data" : "pac_id", "width" : "30px", "orderable" : false, "className" : "text-center", render : function (a,b,c) {
                        return c.customers <= 0 ? '<span class="label label-danger">'+c.customers+'</span>' : '<span class="label label-success">'+c.customers+'</span>';
                    }
                },
                { "data" : "cap", "className" : "text-center", "width" : "50px", render : function (a,b,c) {
                        return c.cap+' '+c.cap_byte;
                    }
                },
                { "data" : "price", "width" : "120px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.price;
                    }
                },
                { "data" : "tax_percent", "width" : "50px", "className" : "text-center", render : function (a,b,c) {
                        return c.tax_percent+' %';
                    }
                },
                { "data" : "price_with_tax", "width" : "120px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.price_with_tax;
                    }
                }
            ]
        });
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select onchange="table._fnDraw()" class="cab-id custom-select custom-select-sm form-control form-control-sm">' +
                @if(strlen(Auth::user()->cab_id)==0)
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
