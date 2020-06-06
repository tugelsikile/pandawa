@csrf
<div class="form-inputnya">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="nama_cabang">Nama Cabang</label>
        <div class="col-sm-10">
            <select name="nama_cabang" class="form-control" id="nama_cabang">
                <option value="">=== Semua Cabang ===</option>
                @if(!is_null($cabangs))
                    @foreach($cabangs as $key => $cabang)
                        <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="bulan_tagihan">Bulan Tagihan</label>
        <div class="col-sm-4">
            <select name="bulan_tagihan" id="bulan_tagihan" class="form-control">
                @foreach(ArrayBulan() as $bulan)
                    <option @if($bulan['value']==date('m')) selected @endif value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>
                @endforeach
            </select>
        </div>
        <label class="col-sm-2 col-form-label" for="tahun_tagihan">Tahun Tagihan</label>
        <div class="col-sm-4">
            <select name="tahun_tagihan" id="tahun_tagihan" class="form-control">
                @for($tahun = MinTahun(); $tahun <= date('Y'); $tahun++)
                    <option @if($tahun == date('Y')) selected @endif value="{{ $tahun }}">{{ $tahun }}</option>
                @endfor
            </select>
        </div>
    </div>
</div>
<div class="form-progressnya">
    <div class="progress" style="height:50px">
        <div class="progress-bar progress-bar-striped progress-bar-animated"
             role="progressbar"
             aria-valuenow="0"
             aria-valuemin="0"
             aria-valuemax="100"
             style="width:0%">
            0%
        </div>
    </div>
</div>
<script>
    $('.form-progressnya').hide();
    $('#ModalForm').attr({'onsubmit':null});
    $('#ModalForm').submit(function () {
        $('.form-inputnya').hide();
        $('.form-progressnya').show();
        Swal.fire({
            title               : 'Perhatian !',
            text                : 'Mulai Proses Generate Invoice?<br>asdasd',
            icon                : 'warning',
            showCancelButton    : true,
            confirmButtonColor  : '#3085d6',
            cancelButtonColor   : '#d33',
            confirmButtonText   : 'Hapus',
            cancelButtonText    : 'Batal',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url     : url,
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : { _token : token, id : id, data_status : status },
                    error   : function (e) {
                        var msg = '';
                        var jsonResponse = e.responseJSON;
                        if (jsonResponse){
                            jsonResponse = jsonResponse.message;
                            jsonResponse = jsonResponse.split('#');
                            msg = '<ul>';
                            $.each(jsonResponse,function (i,v) {
                                msg += '<li>'+v+'</li>';
                            });
                            msg += '</ul>';
                        }
                        showError(e.statusText+'<br>'+msg);
                    },
                    success : function (e) {
                        if (e.code == 1000){
                            if (typeof table !== 'undefined'){
                                table._fnDraw(false);
                            }
                            showSuccess(e.msg);
                        } else {
                            showError(e.msg);
                        }
                    }
                });
            }
        });
        return false;
    });
    $('#nama_cabang,#bulan_tagihan,#tahun_tagihan').select2();
</script>