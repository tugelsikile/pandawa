@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row kartu-tagihan mb-3">
            <div class="col-sm-3">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;" onclick="$('html,body').animate({scrollTop : $('#saldo-awal').offset().top - 70},'slow')">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Saldo Awal Bulan</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="saldo-awal">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;" onclick="$('html,body').animate({scrollTop : $('#pemasukan').offset().top - 70},'slow')">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Pemasukan</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="total-pendapatan">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;" onclick="$('html,body').animate({scrollTop : $('#pengeluaran').offset().top - 70},'slow')">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Pengeluaran</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="total-pengeluaran">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;" onclick="$('html,body').animate({scrollTop : $('#piutang').offset().top - 70},'slow')">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Piutang</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="total-piutang">0</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="toast" data-autohide="false" style="width:100% !important;min-width:100% !important;" onclick="$('html,body').animate({scrollTop : $('#saldo-akhir').offset().top - 70},'slow')">
                    <div class="toast-header">
                        <i class="fa fa-desktop mr-2"></i>
                        <strong class="mr-auto">Saldo Akhir Bulan</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Rp. <strong class="saldo-akhir">0</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Kas Keuangan</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Uraian</th>
                            <th>Jenis</th>
                            <th class="min-mobile">Jumlah</th>
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
        var groupColumn = 1;
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar"B><"row clearfix"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "ordering"      : false,
            "paging"        : false,
            "filter"        : false,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ route('admin-kas.table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token    = '{{ csrf_token() }}';
                    d.bulan     = $('div.toolbar select.month').length !== 0 ? $('div.toolbar select.month').val() : '{{ date('m') }}';
                    d.tahun     = $('div.toolbar select.year').length !== 0 ? $('div.toolbar select.year').val() : '{{ date('Y') }}';
                }
            },
            buttons         : [
                @if($privs->C_opt == 1)
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Kas',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-kas/create') }}','title':'Tambah Kas'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                },
                @endif
            ],
            "columnDefs": [
                { "visible": false, "targets": groupColumn }
            ],
            "order": [[ groupColumn, 'asc' ]],
            "columns"   : [
                { "data" : "informasi", render : function (a,b,c,d) {
                        var html = '';
                        var informasi = '';
                        if (c.created_by === 'automated'){
                            informasi = c.informasi
                        } else {
                            informasi = c.kas_date+'<br><span class="badge badge-secondary">'+c.nomor_bukti+'</span> '+c.informasi
                            html = '' +
                            @if($privs->U_opt == 1 || $privs->D_opt == 1)
                                '<div class="dropdown show float-right">' +
                                    '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    @if($privs->U_opt == 1)
                                        '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Kas" href="{{ url('admin-kas/update?id=') }}'+c.id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                    @endif
                                    @if($privs->D_opt == 1)
                                        '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Kas" data-id="'+c.id+'" onclick="delete_data(this);return false" href="{{ url('admin-kas/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                    @endif
                                    '</div>' +
                                '</div>' +
                            @endif
                            '';
                        }
                        if (c.kategori === 'saldo awal'){
                            html = '<a title="Rubah Saldo Awal" onclick="show_modal(this);return false" href="{{ url('admin-kas/update-saldo-awal') }}?id='+c.id+'" class="btn btn-primary btn-sm float-right"><i class="fa fa-pencil"></i></a>';
                        }
                        return html + informasi;
                    }
                },
                { "data" : "kategori", "width" : "120px" },
                { "data" : "ammount", "width" : "120px", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ) }
            ],
            "drawCallback" : function (settings,aa,bb,cc) {
                var metaSaldo = settings.jqXHR.responseJSON;
                $('.saldo-awal').html(metaSaldo.saldo_awal);
                $('.saldo-akhir').html(metaSaldo.saldo_akhir);
                $('.total-pendapatan').html(metaSaldo.pendapatan);
                $('.total-pengeluaran').html(metaSaldo.pengeluaran);
                $('.total-piutang').html(metaSaldo.piutang);
                if ($('div.toolbar .dt-buttons .float-right').length == 0){
                    $('div.toolbar .dt-buttons').append('' +
                        '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                        '<select onchange="table._fnDraw()" class="month mb-2 custom-select custom-select-sm form-control form-control-sm">' +
                            @foreach(ArrayBulan() as $key => $bulan)
                                '<option @if($bulan['value']==date('m')) selected @endif value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>' +
                            @endforeach
                                '</select>' +
                        '<select onchange="table._fnDraw()" class="year mb-2 custom-select custom-select-sm form-control form-control-sm">' +
                            @for($tahun = MinTahun(); $tahun <= date('Y'); $tahun++)
                                '<option value="{{ $tahun }}">{{ $tahun }}</option>' +
                            @endfor
                                '</select>' +
                        '</div>' +
                        '');
                }
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group" bgcolor="#ccc"><td colspan="3" style="text-transform: capitalize;font-weight:bold"><a id="'+group.replace(' ','-')+'"></a>'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    </script>

@endsection
