@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Kas Keuangan</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Uraian</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th class="min-mobile">Saldo</th>
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
            "dom"           : '<"mb-2 toolbar"B><"row clearfix"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            /*"searchDelay"   : 2000,*/
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
            "drawCallback" : function () {
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
            "columns"   : [
                { "data" : "informasi" },
                { "data" : "kategori", "width" : "120px" },
                { "data" : "ammount", "width" : "120px" },
                { "data" : "saldo", "width" : "120px" }
            ]
        });
    </script>

@endsection
