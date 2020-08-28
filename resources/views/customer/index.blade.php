@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Customer</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile"><input type="checkbox" onclick="tableCbxAll(this)"></th>
                            <th class="min-mobile">Nama Pelanggan</th>
                            <th class="min-desktop">Alamat</th>
                            <th class="min-desktop">Produk</th>
                            <th class="min-desktop">Tagihan Bulan Ini</th>
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
        var table = $('#dataTable').dataTable({
            "dom"           : '<"mb-2 toolbar"B><"row clearfix"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-customer/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                    @if(Auth::user()->cab_id)
                        d.cab_id = '{{ Auth::user()->cab_id }}';
                    @else
                        d.cab_id = $('.cab-id').val();
                    @endif
                    d.is_active = $('.is-active').val();
                    d.npwp = $('.npwp').val();
                    d.mitra = $('.mitra').val();
                    d.jenis_layanan = $('.jenis-layanan').val();
                    d.pac_id = $('.pac-id').val();
                    d.date_range = $('.date-range').val();
                }
            },
            "drawCallback"  : function (settings) {
                if ($('div.toolbar div.float-right').length === 0){
                    $('div.toolbar .dt-buttons').append('' +
                        @if($privs->R_opt == 1)
                            '<div class="float-right d-none d-md-block col-sm-6 pr-0">' +
                                '<div class="row">' +
                                    '<div class="col-sm-6">' +
                                        '<select name="mitra" onchange="cari_mitra()" class="mb-1 mitra custom-select custom-select-sm form-control form-control-sm">' +
                                            '<option value="">=== Cabang / Mitra ===</option>' +
                                            '<option value="1">Mitra</option>' +
                                            '<option value="0">Cabang</option>' +
                                        '</select>' +
                                        '<select onchange="cari_paketan()" class="mb-1 cab-id custom-select custom-select-sm form-control form-control-sm">' +
                                            @if(strlen(auth()->user()->cab_id)===0)
                                                '<option value="">=== Semua Cabang / Mitra ===</option>' +
                                            @endif
                                            @if($cabangs)
                                                @foreach($cabangs as $key => $cabang)
                                                    '<option @if($cabang->cab_id == auth()->user()->cab_id) selected @endif value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>' +
                                                @endforeach
                                            @endif
                                        '</select>' +
                                        '<select name="pac_id" onchange="table._fnDraw()" class="mb-1 pac-id custom-select custom-select-sm form-control form-control-sm">' +
                                            '<option value="">=== Pilih Nama Produk ===</option>' +
                                            @forelse($paketan as $paket)
                                                '<option value="{{$paket->pac_id}}">{{$paket->pac_name}} - @isset($paket->cabang->cab_name) {{$paket->cabang->cab_name}} @endisset</option>' +
                                            @empty
                                                '<option value="">Tidak ada data produk===</option>' +
                                            @endforelse
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col-sm-6">' +
                                        '<input placeholder="Tanggal pemasangan / berlangganan" type="text" name="date_range" class="date-range form-control form-control-sm mb-2" value="">' +
                                        '<select onchange="table._fnDraw()" class="is-active mb-1 custom-select custom-select-sm form-control form-control-sm">' +
                                            '<option value="">=== Status Aktif ===</option>' +
                                            '<option value="1">Aktif</option>' +
                                            '<option value="0">Non Aktif</option>' +
                                        '</select>' +
                                        '<select onchange="table._fnDraw()" class="npwp mb-1 custom-select custom-select-sm form-control form-control-sm">' +
                                            '<option value="">=== Status NPWP ===</option>' +
                                            '<option value="1">Punya NPWP</option>' +
                                            '<option value="0">Tidak Punya NPWP</option>' +
                                        '</select>' +
                                        '<select onchange="table._fnDraw()" class="jenis-layanan mb-1 custom-select custom-select-sm form-control form-control-sm">' +
                                            '<option value="">=== Semua Jenis Layanan ===</option>' +
                                            @if($jenisLayanan)
                                                @forelse($jenisLayanan as $item)
                                                    '<option value="{{$item->id}}">{{$item->name}}</option>' +
                                                @empty
                                                @endforelse
                                            @endif
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        @endif
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
            buttons         : [
                {
                    className   : 'btn btn-sm btn-primary',
                    text        : '<i class="fa fa-plus"></i> Tambah Pelanggan',
                    action      : function (e,dt,node,config) {
                        @if($privs->C_opt == 1)
                            show_modal({'href':'{{ url('admin-customer/create') }}','title':'Tambah Pelanggan'});
                        @else
                            showError('Forbidden Action');
                        @endif
                    }
                },
                {
                    className   : 'btn btn-sm btn-danger',
                    text        : '<i class="fa fa-trash-o"></i> Hapus Pelanggan Terpilih',
                    action      : function (e, dt, node, config) {
                        @if($privs->D_opt == 1)
                            bulk_delete({'title':'Hapus Pelanggan Terpilih','href':'{{ url('admin-customer/bulk-delete') }}','data-token':'{{csrf_token()}}'});
                        @endif
                    }
                }
            ],
            "columns"   : [
                { "data" : "is_active", "orderable" : false, "width" : "30px", "className" : "text-center", render : function (a,b,c) {
                    return '<input type="checkbox" name="cust_id[]" value="' + c.cust_id + '">';
                    }
                },
                { "data" : "fullname", render : function (a,b,c) {
                    var html = '' +
                        @if($privs->U_opt == 1 || $privs->D_opt == 1)
                            '<div class="dropdown show float-right">' +
                                '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                    '<a class="dropdown-item" href="{{ url('admin-customer/detail?id=') }}'+c.cust_id+'"><i class="fa fa-user-circle"></i> Detail Pelanggan</a>' +
                                @if($privs->U_opt == 1)
                                    '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Customer" href="{{ url('admin-customer/update?id=') }}'+c.cust_id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                                @endif
                                @if($privs->D_opt == 1)
                                    '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Customer" data-id="'+c.cust_id+'" onclick="delete_data(this);return false" href="{{ url('admin-customer/delete') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                                @endif
                                '</div>' +
                            '</div>' +
                        @endif
                        '';
                        html += c.fullname+'<br><span class="badge badge-secondary">'+c.kode+'</span>&nbsp;';
                        html += !c.cabang ? '' : '<span class="badge badge-primary">' + c.cabang.cab_name + '</span>';
                        return html;
                    }
                },
                { "data" : "address_01", "width" : "250px" },
                { "data" : "package", "orderable" : false, "width" : "150px", render : function (a,b,c) {
                    return !c.package ? '' : c.package.pac_name;
                    }
                },
                { "data" : "is_active", "orderable" : false, "width" : "150px", "className" : "text-right", render : function (a,b,c) {
                    return !c.invoice ? '' : 'Rp. '+Math.round(c.invoice.price_with_tax).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    }
                },
                { "data" : "is_active", "width" : "50px", "className" : "text-center", render : function (a,b,c) {
                        if (c.is_active == 1){
                            btn_aktif = '<a @if($privs->U_opt == 1) onclick="setStatusAktif(this);return false" data-token="{{ csrf_token() }}" data-value="0" data-id="'+c.cust_id+'" title="Non Aktifkan Pelanggan" href="{{ url('admin-customer/set-status') }}" @endif class="btn-sm btn btn-block btn-success">Aktif<br>'+tanggalIndo(c.from_date)+' '+bulanIndo(c.from_date)+' '+tahunIndo(c.from_date)+'</a>';
                        } else {
                            btn_aktif = '<a @if($privs->U_opt == 1) onclick="setStatusAktif(this);return false" data-token="{{ csrf_token() }}" data-value="1" data-id="'+c.cust_id+'" title="Aktifkan Pelanggan" href="{{ url('admin-customer/set-status') }}" @endif class="btn-sm btn btn-block btn-danger">Non Aktif<br>'+tanggalIndo(c.inactive_date)+' '+bulanIndo(c.inactive_date)+' '+tahunIndo(c.inactive_date)+'</a>';
                        }
                        return btn_aktif;
                    }
                }
            ]
        });

    </script>

@endsection
