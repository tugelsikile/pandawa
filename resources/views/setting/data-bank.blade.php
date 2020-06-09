<table class="table table-bordered" id="dataTable">
    <thead>
    <tr>
        <th class="min-mobile">Nama Akun</th>
        <th class="min-desktop">Nama Bank</th>
        <th class="min-mobile">Status</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    var table = $('#dataTable').dataTable({
        "dom"           : '<"mb-2 toolbar"B><"row"<"col-sm-8"l><"col-sm-4"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
        "lengthMenu"    : [[30, 60, 120, 240, 580], [30, 60, 120, 240, 580]],
        "order"         : [[ 0, "asc" ]],
        "searchDelay"   : 2000,
        "fixedHeader"   : true,
        "responsive"    : true,
        "deferRender"   : true,
        "processing"    : true,
        "serverSide"    : true,
        "ajax"          : {
            "url"   : '{{ url('setting/data-bank-tabel') }}',
            "type"  : "POST",
            "data"  : function (d) {
                d._token = '{{ csrf_token() }}';
            }
        },
        buttons         : [
            {
                className : 'btn btn-sm btn-primary',
                text: '<i class="fa fa-plus"></i> Tambah Bank',
                action : function (e,dt,node,config) {
                    show_modal({'href':'{{ url('setting/create-bank') }}','title':'Tambah Bank'});
                }
            }
        ],
        "columns"   : [
            { "data" : "bank_fullname", render : function (a,b,c) {
                    var html = '' +
                    '<div class="dropdown show float-right">' +
                        '<a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
                        '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                            '<a class="dropdown-item" onclick="show_modal(this);return false" title="Rubah Data Bank" href="{{ url('setting/update-bank?id=') }}'+c.bank_id+'"><i class="fa fa-pencil"></i> Rubah Data</a>' +
                            '<a class="dropdown-item" data-token="{{ csrf_token() }}" title="Hapus Data Bank" data-id="'+c.bank_id+'" onclick="delete_data(this);return false" href="{{ url('setting/delete-bank') }}"><i class="fa fa-trash-o"></i> Hapus Data</a>' +
                        '</div>' +
                    '</div>';
                    return html+c.bank_fullname+'<br><span class="badge badge-primary">'+c.bank_rekening+'</span>';
                }
            },
            { "data" : "bank_name", "width" : "170px", render : function (a,b,c) {
                    return c.bank_name+'<br><span class="badge badge-primary">' + c.bank_cabang + '</span>';
                }
            },
            { "data" : "status_active", "width" : "100px", render : function (a,b,c) {
                    var html = '';
                    if (c.status_active == 1){
                        html = '<a onclick="setStatusAktif(this);return false" href="{{ url('setting/set-status-bank') }}" data-token="{{ csrf_token() }}" data-id="'+c.bank_id+'" data-value="0" title="Non Aktifkan Data Bank" class="btn btn-sm btn-block btn-success">Aktif</a>';
                    } else {
                        html = '<a onclick="setStatusAktif(this);return false" href="{{ url('setting/set-status-bank') }}" data-token="{{ csrf_token() }}" data-id="'+c.bank_id+'" data-value="1" title="Aktifkan Data Bank" class="btn btn-sm btn-block btn-danger">Non Aktif</a>';
                    }
                    return html;
                }
            }
        ]
    });
</script>