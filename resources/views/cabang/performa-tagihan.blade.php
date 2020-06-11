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
        function bulanIndo(string) {
            var arr = string.split("-");
            var months = [ "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December" ];
            var month_index =  parseInt(arr[1],10) - 1;
            return months[month_index];
        }
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
                    d.cab_id = $('.cab-id').val();
                }
            },
            buttons         : [
                {
                    className : 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-print"></i> Cetak Data',
                    action : function (e,dt,node,config) {
                        show_modal({'href':'{{ url('admin-cabang/cetak-performa-tagihan') }}','title':'Cetak Data'});
                    }
                },
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-download"></i> Download Excel',
                    action      : function () {
                        
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
        $('div.toolbar .dt-buttons').append('' +
            '<div class="float-right d-none d-md-block col-sm-3 pr-0">' +
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
            '</div>');
    </script>

@endsection
