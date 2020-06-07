@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Tagihan</div>
            <div class="card-body is-print" style="display: none">
                <div class="toolbar mb-2">
                    <a onclick="printNow();return false" href="javascript:;" class="btn btn-sm btn-outline-primary btn-print"><i class="fa fa-print"></i> Cetak</a>
                    <a onclick="printCancel();return false" href="javascript:;" class="btn btn-sm btn-outline-primary btn-cancel-print"><i class="fa fa-close"></i> Tutup</a>
                </div>
                <iframe src="{{ url('api/cetak-loading') }}" name="printFrame" id="printFrame" style="height:600px;border:solid 1px #ccc;width:100%;padding:10px"></iframe>
            </div>
            <div class="card-body no-print">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">No</th>
                            <th class="min-mobile">Nomor Invoice / Nama Pelanggan</th>
                            <th class="min-desktop">Periode</th>
                            <th class="min-desktop">Jml Tagihan</th>
                            <th class="min-desktop">Status Pembayaran</th>
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
            "dom"           : '<"mb-2 toolbar clearfix"B><"row"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-tagihan/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token    = '{{ csrf_token() }}';
                    d.cab_id    = $('div.toolbar select.cab-id') ? $('div.toolbar select.cab-id').val() : '';
                    d.is_active = $('div.toolbar select.is-active') ? $('div.toolbar select.is-active').val() : '';
                    d.npwp      = $('div.toolbar select.npwp') ? $('div.toolbar select.npwp').val() : '';
                    d.inv_paid  = $('div.toolbar select.inv-paid') ? $('div.toolbar select.inv-paid').val() : '';
                    if ($('div.toolbar select.inv-month').length == 0){
                        d.inv_month = '{{ date('m') }}';
                    } else {
                        d.inv_month = $('div.toolbar select.inv-month').val();
                    }
                    if ($('div.toolbar select.inv-year').length == 0){
                        d.inv_year = '{{ date('Y') }}';
                    } else {
                        d.inv_year = $('div.toolbar select.inv-year').val();
                    }
                }
            },
            buttons         : [
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-refresh"></i> Generate Invoice',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-tagihan/generate-invoice') }}','title':'Generate Invoice'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                },
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Tagihan',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-tagihan/create') }}','title':'Buat Tagihan Manual'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                },
                {
                    className   : 'btn btn-sm btn-outline-primary',
                    text        : '<i class="fa fa-print"></i> Cetak Laporan',
                    action      : function (e,dt,node,config) {
                        printDataPost({'href':'{{ url('api/tagihan/cetak-laporan') }}','data-frame':'printFrame','data-form':'FormTable'});
                    }
                },
                {
                    className   : 'btn btn-sm btn-outline-secondary',
                    text        : '<i class="fa fa-print"></i> Cetak Rekap',
                    action      : function (e,dt,node,config) {
                        printDataPost({'href':'{{ url('api/tagihan/cetak-rekap') }}','data-frame':'printFrame','data-form':'FormTable'});
                    }
                }
            ],
            "columns"   : [
                { "data" : "inv_id", "className" : "text-center", "width" : "30px", "orderable" : false, render : function (a,b,c,d) {
                        return d.row + d.settings._iDisplayStart + 1;
                    }
                },
                { "data" : "inv_number", render : function (a,b,c) {
                        return '<a onclick="printData(this);return false" href="{{ url('api/tagihan/cetak-invoice?id=') }}'+c.inv_id+'" class="btn btn-sm btn-outline-primary float-right"><i class="fa fa-print"></i></a>'+c.fullname+'<br><span class="badge badge-primary">'+ c.inv_number + '</span> <span class="badge badge-success" onclick="$(\'.cab-id\').val('+c.cab_id+');table._fnDraw()">'+c.cabang.cab_name+'</span>';
                    }
                },
                { "data" : "inv_date", render : function (a,b,c) {
                        return c.periode;
                    }
                },
                { "data" : "price_with_tax", "width" : "150px", render : function (a,b,c) {
                        var nama_paket = !c.paket ? null : c.paket.pac_name;
                        return '<small class="badge badge-primary">'+nama_paket+'</small><br>Rp. '+c.harga;
                    }
                },
                { "data" : "is_paid", "width" : "120px", render : function (a,b,c) {
                        var html = '';
                        if (c.is_paid == 1){
                            html = '<a onclick="@if(checkPrivileges('admin-tagihan','approve-paid')->R_opt == 1) show_modal(this); @else showError(\'Forbidden Action\'); @endif return false" title="Batalkan Approval Tagihan" href="@if(checkPrivileges('admin-tagihan','approve-paid')->U_opt == 1){{ url('admin-tagihan/cancel-tagihan?id=') }}'+c.inv_id+'@else javascript:; @endif" class="btn btn-block btn-sm btn-success">Sudah Dibayar<br>'+c.tgl_bayar+'</a>'
                        } else {
                            html = '<a onclick="@if(checkPrivileges('admin-tagihan','cancel-paid')->R_opt == 1) show_modal(this); @else showError(\'Forbidden Action\'); @endif return false" title="Approval Tagihan" href="@if(checkPrivileges('admin-tagihan','approve-paid')->U_opt == 1){{ url('admin-tagihan/approve-tagihan?id=') }}'+c.inv_id+'@else javascript:; @endif" class="btn btn-block btn-danger btn-sm">Belum Dibayar</a>'
                        }
                        return html;
                    }
                }
            ]
        });
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select name="nama_cabang" onchange="table._fnDraw()" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                    @if(strlen(Auth::user()->cab_id)==0)
                        '<option value="">=== Semua Cabang ===</option>' +
                    @endif
                    @if($cabangs)
                        @foreach($cabangs as $key => $cabang)
                            '<option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>' +
                        @endforeach
                    @endif
                '</select>' +
                /*'<select onchange="table._fnDraw()" class="mb-2 cust-id custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Semua Pelanggan ===</option>' +
                '</select>' +*/
                '<select name="npwp" onchange="table._fnDraw()" class="mb-2 npwp custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Status NPWP ===</option>' +
                    '<option value="1">Punya NPWP</option>' +
                    '<option value="0">Tidak Punya NPWP</option>' +
                '</select>' +
                '<select name="is_active" onchange="table._fnDraw()" class="mb-2 is-active custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Status Aktif ===</option>' +
                    '<option value="1">Pelanggan Aktif</option>' +
                    '<option value="0">Pelanggan Non Aktif</option>' +
                '</select>' +
            '</div>' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select name="bulan_tagihan" onchange="table._fnDraw()" class="mb-2 inv-month custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Bulan Tagihan ===</option>' +
                    @foreach(ArrayBulan() as $key => $bulan)
                        '<option @if($bulan['value']==date('m')) selected @endif value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>' +
                    @endforeach
                '</select>' +
                '<select name="tahun_tagihan" onchange="table._fnDraw()" class="mb-2 inv-year custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Tahun Tagihan ===</option>' +
                    @if(strlen($minTahun)>0)
                        @for($tahun = $minTahun; $tahun <= date('Y'); $tahun++)
                            '<option @if($tahun == date('Y')) selected @endif value="{{ $tahun }}">{{ $tahun }}</option>' +
                        @endfor
                    @endif
                '</select>' +
                '<select name="status_bayar" onchange="table._fnDraw()" class="mb-2 inv-paid custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="">=== Status Pembayaran ===</option>' +
                    '<option value="1">Sudah Dibayar</option>' +
                    '<option value="0">Belum Dibayar</option>' +
                '</select>' +
            '</div>');
    </script>

@endsection
