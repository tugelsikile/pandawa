@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Performa Tagihan Cabang
            </div>
            <div class="card-body is-print" style="display: none">
                <div class="toolbar mb-2">
                    <a onclick="printNow();return false" href="javascript:;" class="btn btn-sm btn-outline-primary btn-print"><i class="fa fa-print"></i> Cetak</a>
                    <a onclick="printCancel();return false" href="javascript:;" class="btn btn-sm btn-outline-primary btn-cancel-print"><i class="fa fa-close"></i> Tutup</a>
                </div>
                <iframe src="{{ url('api/cetak-loading') }}" name="printFrame" id="printFrame" style="height:600px;border:solid 1px #ccc;width:100%;padding:10px"></iframe>
            </div>
            <div class="card-body no-print">
                <form id="FormTable">
                    @csrf
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Nama Pelanggan</th>
                            <th class="min-desktop">Cabang</th>
                            <th class="min-desktop">Tunggakan (Bulan)</th>
                            <th class="min-desktop">Besar Tunggakan (Total)</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right" colspan="3">Grand Total</th>
                            <th class="text-right grand-total">0</th>
                        </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar clearfix"B><"row"<"col-sm-8"><"col-sm-4"f>>rt<"row"<"col-sm-6"><"col-sm-6">>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "responsive"    : true,
            "fixedHeader"   : true,
            "searchDelay"   : 2000,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-cabang/performa-tagihan') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    @if(strlen(auth()->user()->cab_id)===0)
                        d.cab_id = $('.cab-id').length === 0 ? '' : $('.cab-id').val();
                    @else
                        d.cab_id = '{{ auth()->user()->cab_id }}';
                    @endif
                    d.mitra = $('.mitra').val();
                    d.jenis = $('.jenis-layanan').val();
                }
            },
            "drawCallback" : function (a,b,c,d) {
                console.log(a.json.total_tagihan);
                $('.grand-total').html(a.json.total_tagihan);
                if ($('div.toolbar .float-right').length === 0){
                    $('div.toolbar .dt-buttons').append('' +
                        '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                            '<select name="mitra" onchange="cari_mitra();" class="mb-2 mitra custom-select custom-select-sm form-control form-control-sm">' +
                                '<option value="">=== Jenis Cabang ===</option> ' +
                                '<option value="0">Cabang</option> ' +
                                '<option value="1">Mitra</option> ' +
                            '</select>' +
                            '<select name="nama_cabang" onchange="table._fnDraw();" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                            @if(strlen(Auth::user()->cab_id)==0)
                                '<option value="">=== Semua Cabang ===</option>' +
                            @endif
                            @if($cabangs)
                                @foreach($cabangs as $key => $cabang)
                                    '<option @if($request->id == $cabang->cab_id) selected @endif value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>' +
                                @endforeach
                            @endif
                            '</select>' +
                            '<select name="jenis" onchange="table._fnDraw();" class="mb-2 jenis-layanan custom-select custom-select-sm form-control form-control-sm">' +
                                '<option value="">=== Semua Jenis Layanan ===</option>' +
                                @forelse($jenis as $item)
                                    '<option value="{{$item->id}}">{{$item->name}}</option>' +
                                @empty
                                @endforelse
                            '</select>' +
                        '</div>');
                }
            },
            buttons         : [
                {
                    className : 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-print"></i> Cetak Data',
                    action : function (e,dt,node,config) {
                        printDataPost({'href':'{{ url('admin-cabang/cetak-performa-tagihan') }}','data-frame':'printFrame','data-form':'FormTable'});
                    }
                },
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-download"></i> Download Excel',
                    action      : function () {
                        var url = '{{ url('admin-cabang/download-performa-tagihan') }}?id=' + $('.cab-id').val() + '&mitra=' + $('.mitra').val() + '&jenis='+$('jenis').val();
                        window.open(url,'_blank');
                    }
                }
            ],
            "columns"   : [
                { "data" : "fullname", render : function (a,b,c) {
                     return '<span class="badge badge-secondary mr-2">'+c.kode+'</span>'+c.fullname;
                    }
                },
                { "data" : "fullname", "width" : "200px", "orderable" : false, render : function (a,b,c) {
                    return '<span class="badge badge-secondary">'+ c.cabang != null ? c.cabang.cab_name : '-' + '</span>';
                    }
                },
                { "data" : "fullname", "width" : "200px", "orderable" : false, render : function (a,b,c) {
                    var bulan = '';
                    $.each(c.tagihan,function (i,v) {
                        v.inv_date != null ? bulan += '<span class="badge badge-secondary">'+bulanIndo(v.inv_date)+'</span> ' : null;
                    });
                    return bulan;
                    }
                },
                { "data" : "fullname", "width" : "200px", "orderable" : false, render : function (a,b,c) {
                    var html = 0;
                    $.each(c.tagihan,function (i,v) {
                        html = html + parseInt(v.price_with_tax);
                    });
                    html = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(html);
                    return html;
                    }
                }
            ]
        });

    </script>

@endsection
