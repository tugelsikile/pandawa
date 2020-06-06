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
        Swal.fire({
            title               : 'Perhatian !',
            html                : 'Mulai Proses Generate Invoice?<br>Invoice yang sudah dibuat dari hasil generate sebelumnya mungkin akan berubah.<br><small>Jendela tidak dapat ditutup sampai proses selesai</small>',
            icon                : 'warning',
            showCancelButton    : true,
            confirmButtonColor  : '#3085d6',
            cancelButtonColor   : '#d33',
            confirmButtonText   : 'Lanjutkan',
            cancelButtonText    : 'Batal',
        }).then((result) => {
            if (result.value) {
                $('#MyModal').data('bs.modal')._config.backdrop = 'static';
                $('#MyModal').data('bs.modal')._config.keyboard = false;
                $('#MyModal .modal-footer .btn').prop({'disabled':true});
                $('#MyModal .modal-header .close').hide();
                $('.form-inputnya').hide();
                $('.form-progressnya').show();
                $('.progress-bar').css({'width':'100%'}).prop({'aria-valuenow':100}).html('Mohon Tunggu. Sedang mengambil data Customer Aktif.<br>Jangan tutup jendela ini sampai proses selesai!');
                $.ajax({
                    url     : '{{ url('admin-tagihan/generate-invoice') }}',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : $('#ModalForm').serialize(),
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
                        showError(msg);
                        $('#MyModal').data('bs.modal')._config.backdrop = true;
                        $('#MyModal').data('bs.modal')._config.keyboard = true;
                        $('#MyModal .modal-footer .btn').prop({'disabled':false});
                        $('#MyModal .modal-header .close').show();
                        $('.form-inputnya').show();
                        $('.form-progressnya').hide();
                    },
                    success : function (e) {
                        if (e.code < 1000){
                            showError(e.msg);
                            $('#MyModal').data('bs.modal')._config.backdrop = true;
                            $('#MyModal').data('bs.modal')._config.keyboard = true;
                            $('#MyModal .modal-footer .btn').prop({'disabled':false});
                            $('#MyModal .modal-header .close').show();
                            $('.form-inputnya').show();
                            $('.form-progressnya').hide();
                        } else {
                            var total = e.params.length;
                            var percent = 0;
                            $('.progress-bar').css({'width':percent+'%'}).prop({'aria-valuenow':percent}).html(percent+'%');
                            $.each(e.params,function (i,v) {
                                percent = Math.round(((i+1) / total) * 100);
                                $('.progress-bar').css({'width':percent+'%'}).prop({'aria-valuenow':percent}).html(percent+'%<br>Memproses ');
                                $.ajax({
                                    url     : '{{ url('admin-tagihan/generate-invoice-next-step') }}',
                                    type    : 'POST',
                                    dataType: 'JSON',
                                    data    : { _token : '{{ csrf_token() }}', data : v },
                                    async   : false,
                                    cache   : false,
                                    error   : function () {

                                    },
                                    success : function (e) {

                                    }
                                });
                                if (i + 1 >= total ){
                                    $('#MyModal').data('bs.modal')._config.backdrop = true;
                                    $('#MyModal').data('bs.modal')._config.keyboard = true;
                                    $('#MyModal .modal-footer .btn').prop({'disabled':false});
                                    $('#MyModal .modal-header .close').show();
                                    $('.form-inputnya').show();
                                    $('.form-progressnya').hide();
                                }
                            });
                        }
                    }
                });
            }
        });
        return false;
    });
    $('#nama_cabang,#bulan_tagihan,#tahun_tagihan').select2();
</script>