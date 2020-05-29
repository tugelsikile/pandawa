@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-15">
                    @if($privs->C_opt == 1)
                        <a href="" class="btn btn-primary"><i class="fa fa-plus"></i> Cabang Baru</a>
                    @endif
                </div>
                <table class="table table-bordered" id="dataTable" style="width: 100%">
                    <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Nama Cabang</th>
                        <th>Jml Pelanggan</th>
                        <th>Tagihan Bulan Ini</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $('#dataTable').dataTable({
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 1, "asc" ]],
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-cabang/table') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            "columns"   : [
                { "data" : "input", "width" : "20px", "className" : "text-center", "orderable" : false },
                { "data" : "cab_name" },
                { "data" : "customer_count", "width" : "70px", "className" : "text-center" },
                { "data" : "invoice", "width" : "150px" }
            ]
        });
    </script>

@endsection
