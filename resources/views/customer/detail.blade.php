@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <a class="btn btn-sm btn-primary float-right" title="Rubah Data Pelanggan" href="{{ url('admin-customer/update?id=').$data->cust_id }}" onclick="show_modal(this);return false"><i class="fa fa-pencil"></i> Rubah Data</a>
                {{ $data->fullname }}
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a data-href="{{ url('admin-customer/details/pelanggan?id=').$data->cust_id }}" class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Data Pelanggan</a>
                    </li>
                    <li class="nav-item">
                        <a data-href="{{ url('admin-customer/details/perusahaan?id=').$data->cust_id }}" class="nav-link" id="info-perusahaan-tab" data-toggle="tab" href="#info-perusahaan" role="tab" aria-controls="info-perusahaan" aria-selected="false">Info Perusahaan</a>
                    </li>
                    <li class="nav-item">
                        <a data-href="{{ url('admin-customer/details/info-tagihan?id=').$data->cust_id }}" class="nav-link" id="info-tagihan-tab" data-toggle="tab" href="#info-tagihan" role="tab" aria-controls="info-tagihan" aria-selected="false">Info Penagihan</a>
                    </li>
                    <li class="nav-item">
                        <a data-href="{{ url('admin-customer/details/layanan?id=').$data->cust_id }}" class="nav-link" id="info-layanan-tab" data-toggle="tab" href="#info-layanan" role="tab" aria-controls="info-layanan" aria-selected="false">Info Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a data-href="{{ url('admin-customer/details/tagihan?id=').$data->cust_id }}" class="nav-link" id="tagihan-tab" data-toggle="tab" href="#tagihan" role="tab" aria-controls="tagihan" aria-selected="false">Data Tagihan</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active pt-3" id="home" role="tabpanel" aria-labelledby="home-tab"></div>
                    <div class="tab-pane fade pt-3" id="info-perusahaan" role="tabpanel" aria-labelledby="info-perusahaan-tab">...</div>
                    <div class="tab-pane fade pt-3" id="info-tagihan" role="tabpanel" aria-labelledby="info-tagihan-tab">...</div>
                    <div class="tab-pane fade pt-3" id="info-layanan" role="tabpanel" aria-labelledby="info-layanan-tab">...</div>
                    <div class="tab-pane fade pt-3" id="tagihan" role="tabpanel" aria-labelledby="tagihan">...</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        loadTabs('{{ url('admin-customer/details/pelanggan?id=').$data->cust_id }}','#home')
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var url         = $(this).attr('data-href');
            var element     = $(this).attr('href');
            loadTabs(url,element);
        });
        $('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
            var element = '#'+e.target.attributes['aria-controls'].value;
            if ($(element).find('#content').length > 0){
                $(element).find('#content').remove();
            }
        });
        function loadTabs(url,element) {
            if ($(element).find('div').length===0){

            }
            $(element).html('<div class="text-center"><i class="fa fa-spin fa-circle-o-notch"></i> Memuat halaman</div>')
                .load(url,function (a,b,c) {
                    if (b === 'error'){
                        $(element).html('<div class="text-center">' + b + ' : ' + c.status + ' ' + c.statusText + '</div>');
                    }
                });
        }
    </script>

@endsection
