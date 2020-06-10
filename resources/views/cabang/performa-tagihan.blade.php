@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Performa Tagihan Cabang
            </div>
            <div class="card-body">
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
                </table>
            </div>
        </div>
    </div>
    <script>
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar clearfix"B><"row"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "responsive"    : true,
            "deferRender"   : true,
            "fixedHeader"   : true,
            "searchDelay"   : 2000,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-cabang/performa-tagihan') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.cab_id = $('.cab-id').val();
                }
            },
            buttons         : [

            ],
            "columns"   : [
                { "data" : "inv_id", render : function (a,b,c) {
                     return c.customer.fullname;
                    }
                },
                { "data" : "cab_id", render : function (a,b,c) {
                        return c.cabang;
                    }
                },
                { "data" : "inv_date" },
                { "data" : "inv_date" }
            ]
        });
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
                '<select name="nama_cabang" onchange="table._fnDraw();cariInfoTagihan();" class="mb-2 cab-id custom-select custom-select-sm form-control form-control-sm">' +
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
