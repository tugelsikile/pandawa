@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Data Halaman dan Fungsi</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile">Halaman</th>
                            <th class="min-desktop">Fungsi Halaman</th>
                            <th class="min-desktop">Url Halaman</th>
                            <th class="min-desktop">Aksi</th>
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
            "dom"           : '<"mb-2 toolbar"><"row clearfix"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
            "order"         : [[ 0, "asc" ]],
            "searchDelay"   : 2000,
            "fixedHeader"   : true,
            "responsive"    : true,
            "deferRender"   : true,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                "url"   : '{{ url('admin-access/halaman-dan-fungsi') }}',
                "type"  : "POST",
                "data"  : function (d) {
                    d._token = '{{ csrf_token() }}';
                }
            },
            "columns"   : [
                { "data" : "ctrl_name", "width" : "200px" },
                { "data" : "func_name", "width" : "200px" },
                { "data" : "ctrl_url", render : function (a,b,c) {
                        return '{{ url('/') }}/' + c.ctrl_url + '/' + c.func_url;
                    }
                },
                { "data" : "func_id", "width" : "100px", "orderable" : false, render : function (a,b,c) {
                        return '<a title="Hapus halaman" onclick="delete_data(this);return false" href="/admin-access/delete-halaman" data-token="{{ csrf_token() }}" data-id="'+c.func_id+'" class="btn btn-sm btn-block btn-danger"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });
    </script>

@endsection
