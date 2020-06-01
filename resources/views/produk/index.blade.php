@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-15">
                    <div class="pull-right">
                        @if($privs->C_opt == 1)
                            <a title="Tambah Produk" href="{{ url('admin-produk/create') }}" class="btn btn-primary" onclick="show_modal(this);return false"><i class="fa fa-plus"></i> Produk Baru</a>
                        @endif
                        @if($privs->D_opt==1)
                            <a data-token="{{ csrf_token() }}" title="Hapus Produk Terpilih" href="{{ url('admin-produk/bulk-delete') }}" class="btn btn-danger" onclick="bulk_delete(this);return false"><i class="fa fa-trash-o"></i> Hapus Produk Terpilih</a>
                        @endif
                    </div>
                    <select class="cab_id" onchange="table._fnDraw()">
                        <option value="">===Semua Cabang===</option>
                        @if(count($cabangs)>0)
                            @foreach($cabangs as $key=>$cabang)
                                <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="clearfix"></div>
                </div>
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th><input type="checkbox" onclick="tableCbxAll(this)"></th>
                            <th>Nama Produk</th>
                            <th>Jml Pelanggan</th>
                            <th>Kapasitas</th>
                            <th>Harga</th>
                            <th>Pajak</th>
                            <th>Harga Setelah Pajak</th>
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
            "searchDelay"   : 2000,
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-produk/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.cab_id = $('.cab_id').val();
                }
            },
            "columns"   : [
                { "data" : "input", "width" : "30px", "orderable" : false, "className" : "text-center", render : function (a,b,c) {
                        return '<input type="checkbox" name="pac_id[]" value="'+c.pac_id+'">';
                    }
                },
                { "data" : "pac_name", render : function (a,b,c) {
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
                                    '    <li><a onclick="show_modal(this);return false" title="Rubah Data Produk" href="{{ url('admin-produk/update?id=') }}'+c.pac_id+'"><i class="fa fa-pencil"></i> Rubah Data</a></li>\n' +
                                @endif
                                        @if($privs->D_opt == 1)
                                    '    <li><a data-token="{{ csrf_token() }}" title="Hapus Data Produk" data-id="'+c.pac_id+'" onclick="delete_data(this);return false" href="{{ url('admin-produk/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a></li>\n' +
                                @endif
                                    '  </ul>\n' +
                            '</div>\n' +
                                @endif
                            '';
                        return c.pac_name+html;
                    }
                },
                { "data" : "jml", "width" : "30px", "orderable" : false, "className" : "text-center", render : function (a,b,c) {
                        return c.customers <= 0 ? '<span class="label label-danger">'+c.customers+'</span>' : '<span class="label label-success">'+c.customers+'</span>';
                    }
                },
                { "data" : "cap", "className" : "text-center", "width" : "70px", render : function (a,b,c) {
                        return c.cap+' '+c.cap_byte;
                    }
                },
                { "data" : "price", "width" : "130px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.price;
                    }
                },
                { "data" : "tax_percent", "width" : "70px", "className" : "text-center", render : function (a,b,c) {
                        return c.tax_percent+' %';
                    }
                },
                { "data" : "price_with_tax", "width" : "130px", "className" : "text-right", render : function (a,b,c) {
                        return 'Rp. '+c.price_with_tax;
                    }
                }
            ]
        });
    </script>

@endsection
