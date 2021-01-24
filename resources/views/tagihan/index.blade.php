@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row kartu-tagihan mb-3">
            <div class="col-sm-3">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Total Tagihan</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="tagihan-total">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;">
                    <div class="toast-header">
                        <span class="fa-stack mr-2">
                          <i class="fa fa-desktop fa-stack-1x"></i>
                          <i class="fa fa-check fa-stack-1x text-success"></i>
                        </span>
                        <strong class="mr-auto">Tagihan Dibayar</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="tagihan-dibayar">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;">
                    <div class="toast-header">
                        <span class="fa-stack mr-2">
                          <i class="fa fa-desktop fa-stack-1x"></i>
                          <i class="fa fa-check fa-stack-1x text-danger"></i>
                        </span>
                        <strong class="mr-auto">Tunggakan Tagihan</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="tagihan-tunggak">0</strong>
                    </div>
                </div>
            </div>
        </div>
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
                    <table class="table table-bordered table-sm" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile"><input type="checkbox" onclick="tableCbxAll(this);"></th>
                            <th class="min-mobile">No</th>
                            <th class="min-mobile">Nomor Invoice / Nama Pelanggan</th>
                            <th class="min-desktop">Periode</th>
                            <th class="min-desktop">Jml Tagihan</th>
                            <th class="min-desktop">Status</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('.toast').toast('show');
        function cariInfoTagihan() {
            var bulan   = $('div.filters select.inv-month').length === 0 ? '{{ date('m') }}' : $('div.filters select.inv-month').val();
            var tahun   = $('div.filters select.inv-year').length === 0 ? '{{ date('Y') }}' : $('div.filters select.inv-year').val();
            var npwp    = $('div.filters select.npwp').length === 0 ? '' : $('div.filters select.npwp').val();
            var cab_id  = $('div.filters select.cab-id').length === 0 ? '' : $('div.filters select.cab-id').val();
            var is_active = $('div.filters select.is-active').length === 0 ? '' : $('div.filters select.is-active').val();
            var is_paid = $('div.filters select.inv-paid').length === 0 ? '' : $('div.filters select.inv-paid').val();
            var mitra   = $('div.filters select.mitra').length === 0 ? '' : $('div.filters select.mitra').val();
            var jenis   = $('.jenis-layanan').val();
            var token   = '{{ csrf_token() }}';
            var range   = $('.date-range').val();
            var url     = '{{ url('admin-tagihan/informasi') }}'
            tagihanInformasi(url,token,bulan,tahun,cab_id,npwp,is_active,is_paid,mitra,jenis,range);
        }
        var table = $('#dataTable').dataTable({
            "drawCallback": function( settings ) {
                $('.check-count').html(0)
                cariInfoTagihan();
                if ($('div.filters .row').length == 0){
                    $('div.filters').prepend('' +
                        '<div class="row">' +
                            '<div class="col-sm-6">' +
                                '<input placeholder="Range tanggal pembayaran" type="text" name="date_range" class="date-range form-control form-control-sm mb-2" value="">' +
                                '<select name="bulan_tagihan" onchange="table._fnDraw();" class="mb-2 inv-month custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Bulan Tagihan ===</option>' +
                                    @foreach(ArrayBulan() as $key => $bulan)
                                        '<option @if($bulan['value']==date('m')) selected @endif value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>' +
                                    @endforeach
                                '</select>' +
                                '<select name="tahun_tagihan" onchange="table._fnDraw();" class="mb-2 inv-year custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Tahun Tagihan ===</option>' +
                                    @if(strlen($minTahun)>0)
                                        @for($tahun = $minTahun; $tahun <= date('Y'); $tahun++)
                                            '<option @if($tahun == date('Y')) selected @endif value="{{ $tahun }}">{{ $tahun }}</option>' +
                                        @endfor
                                    @endif
                                '</select>' +
                                '<select name="status_bayar" onchange="table._fnDraw();" class="mb-2 inv-paid custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Status Pembayaran ===</option>' +
                                    '<option value="1">Sudah Dibayar</option>' +
                                    '<option value="0">Belum Dibayar</option>' +
                                '</select>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                                '<select name="mitra" onchange="cari_mitra()" class="mb-2 mitra custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Cabang / Mitra ===</option>' +
                                    '<option value="1">Mitra</option>' +
                                    '<option value="0">Cabang</option>' +
                                '</select>' +
                                '<select name="nama_cabang" onchange="table._fnDraw();" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                                    @if(strlen(Auth::user()->cab_id)==0)
                                        '<option value="">=== Semua Cabang / Mitra ===</option>' +
                                    @endif
                                    @if($cabangs)
                                        @foreach($cabangs as $key => $cabang)
                                            '<option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>' +
                                        @endforeach
                                    @endif
                                '</select>' +
                                '<select name="npwp" onchange="table._fnDraw();" class="mb-2 npwp custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Status NPWP ===</option>' +
                                    '<option value="1">Punya NPWP</option>' +
                                    '<option value="0">Tidak Punya NPWP</option>' +
                                '</select>' +
                                '<select name="is_active" onchange="table._fnDraw();" class="mb-2 is-active custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Status Aktif ===</option>' +
                                    '<option value="1">Pelanggan Aktif</option>' +
                                    '<option value="0">Pelanggan Non Aktif</option>' +
                                '</select>' +
                                '<select name="jenis" onchange="table._fnDraw();" class="mb-2 jenis-layanan custom-select custom-select-sm form-control form-control-sm">' +
                                    '<option value="">=== Semua Jenis Layanan ===</option>' +
                                    @forelse($jenis as $item)
                                        '<option value="{{$item->id}}">{{$item->name}}</option>' +
                                    @empty
                                    @endforelse
                                '</select>' +
                            '</div>' +
                        '</div>' +
                        '');
                    $('.date-range').daterangepicker({
                        autoUpdateInput: false,
                        maxDate : '{{\Carbon\Carbon::now()->format('d/m/Y')}}',
                        minDate : '01/01/2020',
                        locale: {
                            format: 'DD/MM/YYYY',
                            cancelLabel: 'Clear'
                        }
                    });
                    $('input[name="date_range"]').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        table._fnDraw();
                    });
                    $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        table._fnDraw();
                    });
                }
            },
            "dom"           : '<"row"<"col-sm-6 mb-2"B><"col-sm-6 filters mb-2"f>><"row"<"col-sm-12"l>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 2, "asc" ]],
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
                    d.mitra         = $('div.filters select.mitra') ? $('div.filters select.mitra').val() : null;
                    @if(Auth::user()->cab_id)
                        d.cab_id    = '{{ Auth::user()->cab_id }}';
                    @else
                        d.cab_id    = $('div.filters select.cab-id') ? $('div.filters select.cab-id').val() : null;
                    @endif
                    d.is_active = $('div.filters select.is-active') ? $('div.filters select.is-active').val() : null;
                    d.npwp      = $('div.filters select.npwp') ? $('div.filters select.npwp').val() : null;
                    d.inv_paid  = $('div.filters select.inv-paid') ? $('div.filters select.inv-paid').val() : null;
                    if ($('div.filters select.inv-month').length===0){
                        d.inv_month = '{{ date('m') }}';
                    } else {
                        d.inv_month = $('div.filters select.inv-month').val();
                    }
                    if ($('div.filters select.inv-year').length===0){
                        d.inv_year = '{{ date('Y') }}';
                    } else {
                        d.inv_year = $('div.filters select.inv-year').val();
                    }
                    d.jenis_layanan = $('.jenis-layanan').val();
                    d.date_range    = $('.date-range').val();
                }
            },
            buttons         : [
                {
                    className   : 'btn btn-sm btn-outline-primary mb-1',
                    text        : '<i class="fa fa-print"></i> Cetak Laporan',
                    action      : function (e,dt,node,config) {
                        printDataPost({'href':'{{ url('api/tagihan/cetak-laporan') }}','data-frame':'printFrame','data-form':'FormTable'});
                    }
                },
                {
                    className   : 'btn btn-sm btn-outline-secondary mb-1',
                    text        : '<i class="fa fa-print"></i> Cetak Rekap',
                    action      : function (e,dt,node,config) {
                        printDataPost({'href':'{{ url('api/tagihan/cetak-rekap') }}','data-frame':'printFrame','data-form':'FormTable'});
                    }
                }
                ,{
                    className   : 'btn btn-sm btn-outline-primary mb-1',
                    text        : '<i class="fa fa-envelope"></i> Kirim Email Invoice',
                    action      : function (e,dt,node,config) {
                        let panjang = $('#dataTable tbody td input:checkbox:checked').length;
                        if (panjang === 0){
                            showError('Pilih tagihan terlebih dahulu');
                        } else {
                            ids = '';
                            $.each($('#dataTable tbody input:checkbox:checked'),function (i,v) {
                                ids += v.value + '-';
                            });
                            let data = {'href':'{{url('admin-tagihan/bulk-send-invoice?id=')}}' + ids,'title':'Kirim Email Tagihan'}
                            show_modal(data);
                        }
                    }
                }
                @if(checkPrivileges('admin_tagihan','index')->C_opt == 1)
                ,{
                    className   : 'btn btn-sm btn-primary mb-1',
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
                    className   : 'btn btn-sm btn-primary mb-1',
                    text        : '<i class="fa fa-plus"></i> Tambah Tagihan',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-tagihan/create') }}','title':'Buat Tagihan Manual'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                }
                @endif
                @if(checkPrivileges('admin_tagihan','approve_paid')->U_opt == 1)
                ,{
                    className   : 'btn btn-sm btn-outline-success mb-1',
                    text        : '<i class="fa fa-check"></i> Approve Tagihan (<span class="check-count">0</span>)',
                    action      : function (e,dt,node,config) {
                        let panjang = $('#dataTable tbody td input:checkbox:checked').length;
                        if (panjang === 0){
                            showError('Pilih tagihan terlebih dahulu');
                        } else {
                            ids = '';
                            $.each($('#dataTable tbody input:checkbox:checked'),function (i,v) {
                                ids += v.value + '-';
                            });
                            let data = {'href':'{{url('admin-tagihan/bulk-approve?id=')}}' + ids,'title':'Approve Tagihan'}
                            show_modal(data);
                        }
                    }
                },
                {
                    className   : 'btn btn-sm btn-outline-warning mb-1',
                    text        : '<i class="fa fa-check"></i> DisApprove Tagihan (<span class="check-count">0</span>)',
                    action      : function (e,dt,node,config) {
                        let panjang = $('#dataTable tbody td input:checkbox:checked').length;
                        if (panjang === 0){
                            showError('Pilih tagihan terlebih dahulu');
                        } else {
                            ids = '';
                            $.each($('#dataTable tbody input:checkbox:checked'),function (i,v) {
                                ids += v.value + '-';
                            });
                            let data = {'href':'{{url('admin-tagihan/bulk-disapprove?id=')}}' + ids,'title':'Disapprove Tagihan'}
                            show_modal(data);
                        }
                    }
                }
                @endif
                @if(checkPrivileges('admin_tagihan','index')->D_opt == 1)
                ,{
                    className   : 'btn btn-sm btn-outline-danger mb-1',
                    text        : '<i class="fa fa-trash-o"></i> Hapus Tagihan',
                    action      : function (e,dt,node,config) {
                        let panjang = $('#dataTable tbody td input:checkbox:checked').length;
                        if (panjang === 0){
                            showError('Pilih tagihan terlebih dahulu');
                        } else {
                            let data = {'title' : 'Hapus Tagihan Dipilih ?', 'href' : '{{url('admin-tagihan/bulk-delete')}}', 'data-token' : '{{csrf_token()}}'};
                            bulk_delete(data);
                        }
                    }
                }
                @endif
            ],
            "columns"   : [
                { "data" : "inv_id", "className" : "text-center", "width" : "30px", "orderable" : false, render : function (a,b,c) {
                        return '<input type="checkbox" name="inv_id[]" value="'+c.inv_id+'">';
                    }
                },
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
        $(document).on('click','#dataTable tbody input:checkbox',function (e) {
            let datalength  = $('#dataTable tbody input:checkbox:checked').length;
            $('.check-count').html(datalength);
        })
    </script>

@endsection
