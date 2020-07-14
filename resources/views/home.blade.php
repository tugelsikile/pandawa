@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-2">
        <div class="card-body">
            Selamat Datang di aplikasi <strong>{{env('APP_NAME')}}</strong>.
            <br>
            Saat ini anda login sebagai <strong>{{ auth()->user()->name }}</strong> dengan hak akses <strong>{{auth()->user()->userLevelOjb->lvl_name}}</strong>.
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    Grafik Tagihan
                    <div class="float-right">
                        <select class="grafik-tagihan-cabang-select custom-select custom-select-sm" onchange="grafikTagihan(this)">
                            @if(!auth()->user()->cab_id)<option value="">=== Cabang ===</option>@endif
                            @forelse($cabangs as $cabang)
                                <option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>
                            @empty
                                <option value="">Tidak ada data cabang</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="card-body grafikCabang">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Pelanggan
                    <div class="float-right">
                        <select class="grafik-customer-select custom-select custom-select-sm" onchange="grafikCustomer(this)">
                            @if(!auth()->user()->cab_id)<option value="">=== Cabang ===</option>@endif
                            @forelse($cabangs as $cabang)
                                <option value="{{$cabang->cab_id}}">{{$cabang->cab_name}}</option>
                            @empty
                                <option value="">Tidak ada data cabang</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="card-body grafikCustomer">

                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script>
        $('.grafik-tagihan-cabang-select,.grafik-customer-select').trigger('change');
        function grafikCustomer(obj) {
            let cab_id = $(obj).val();
            $('.grafikCustomer').html('');
            $.ajax({
                url     : '{{url('api/grafik-customer')}}',
                type    : 'POST',
                data    : { cab_id : cab_id },
                error   : function (e) {
                    $('.grafikCustomer').html(e)
                },
                success : function (e) {
                    $('.grafikCustomer').html(e)
                }
            });
        }
        function grafikTagihan(obj) {
            let cab_id = $(obj).val();
            $('.grafikCabang').html('');
            $.ajax({
                url     : '{{url('api/grafik-tagihan')}}',
                type    : 'POST',
                data    : { cab_id : cab_id },
                error   : function (e) {
                    $('.grafikCabang').html(e)
                },
                success : function (e) {
                    $('.grafikCabang').html(e)
                }
            });
        }
    </script>

</div>
@endsection
