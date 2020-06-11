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
            "dom"           : '<"mb-2 toolbar clearfix"B><"row"<"col-sm-8"><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6">>',
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
                     return c.customer.fullname+'<br><span class="badge badge-secondary">'+c.customer.kode+'</span>';
                    }
                },
                { "data" : "cab_id", "orderable" : false, render : function (a,b,c) {
                        return c.cabang;
                    }
                },
                { "data" : "inv_date", "orderable" : false, render : function (a,b,c) {
                    var bulan = '';
                    $.each(c.bulan,function (i,v) {
                        v.inv_date != null ? bulan += '<span class="badge badge-secondary">'+bulanIndo(v.inv_date)+'</span> ' : null;
                    });
                    return bulan;
                    }
                },
                { "data" : "inv_date", "orderable" : false, render : function (a,b,c) {
                    var html = 0;
                    $.each(c.bulan,function (i,v) {
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
