@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-15">
                    <div class="pull-right">
                        @if($privs->C_opt == 1)
                            <a title="Tambah Pelanggan" href="{{ url('admin-customer/create') }}" class="btn btn-primary" onclick="show_modal(this);return false"><i class="fa fa-plus"></i> Pelanggan Baru</a>
                        @endif
                        @if($privs->D_opt==1)
                            <a data-token="{{ csrf_token() }}" title="Hapus Pelanggan Terpilih" href="{{ url('admin-customer/bulk-delete') }}" class="btn btn-danger" onclick="bulk_delete(this);return false"><i class="fa fa-trash-o"></i> Hapus Pelanggan Terpilih</a>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="cab_id" onchange="table._fnDraw()" style="width:100%">
                                <option value="">=== Semua Cabang ===</option>
                                @if(count($cabangs)>0)
                                    @foreach($cabangs as $key=>$cabang)
                                        <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="is_active" onchange="table._fnDraw()" style="width:100%">
                                <option value="">=== Semua Status ===</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="npwp" onchange="table._fnDraw()" style="width:100%">
                                <option value="">=== Semua NPWP ===</option>
                                <option value="1">NPWP</option>
                                <option value="0">Tanpa NPWP</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th><input type="checkbox" onclick="tableCbxAll(this)"></th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Produk</th>
                            <th>Tagihan Bulan Ini</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('.cab_id,.is_active,.npwp').select2();
        var table = $('#dataTable').dataTable({
            "searchDelay"   : 2000,
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-customer/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.cab_id = $('.cab_id').val();
                    d.is_active = $('.is_active').val();
                    d.npwp = $('.npwp').val();
                }
            },
            "columns"   : [
                { "data" : "input", "orderable" : false, "width" : "30px", "className" : "text-center", render : function (a,b,c) {
                    return '<input type="checkbox" name="cust_id[]" value="' + c.cust_id + '">';
                    }
                },
                { "data" : "fullname", render : function (a,b,c) {
                    var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="btn-group btn-group-xs pull-right">\n' +
                                '<button type="button" class="btn btn-default">Action</button>\n' +
                                '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                                    '<span class="caret"></span>\n' +
                                    '<span class="sr-only">Toggle Dropdown</span>\n' +
                                '</button>\n' +
                                '<ul class="dropdown-menu">\n' +
                                @if($privs->U_opt == 1)
                                    '<li><a onclick="show_modal(this);return false" title="Rubah Data Pelanggan" href="{{ url('admin-customer/update?id=') }}'+c.cust_id+'"><i class="fa fa-pencil"></i> Rubah Data</a></li>\n' +
                                @endif
                                @if($privs->D_opt == 1)
                                    '<li><a data-token="{{ csrf_token() }}" title="Hapus Data Pelanggan" data-id="'+c.cust_id+'" onclick="delete_data(this);return false" href="{{ url('admin-customer/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a></li>\n' +
                                @endif
                                '</ul>\n' +
                            '</div>\n' +
                        @endif
                        '';
                        html += c.fullname+'<br><span class="label label-default">'+c.kode+'</span>&nbsp;';
                        html += !c.cabang ? '' : '<span class="label label-primary">' + c.cabang.cab_name + '</span>';
                        return html;
                    }
                },
                { "data" : "address_01", "width" : "250px" },
                { "data" : "package", "orderable" : false, "width" : "150px", render : function (a,b,c) {
                    return !c.package ? '' : c.package.pac_name;
                    }
                },
                { "data" : "invoice", "orderable" : false, "width" : "150px", "className" : "text-right", render : function (a,b,c) {
                    return !c.invoice ? '' : 'Rp. '+Math.round(c.invoice.price_with_tax).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    }
                },
                { "data" : "is_active", "width" : "80px", "className" : "text-center", render : function (a,b,c) {
                    if (c.is_active == 1){
                        btn_aktif = '<a href="" class="label label-success">Aktif</a>';
                    } else {
                        btn_aktif = '<a href="" class="label label-default">Non Aktif</a>';
                    }
                    return btn_aktif;
                    }
                }
            ]
        });
    </script>

@endsection
