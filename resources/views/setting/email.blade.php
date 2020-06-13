<form id="form-mail-setting">
    @csrf
    <input type="hidden" name="data_setting" value="{{ $data->ms_id }}">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="smtp_host_name">SMTP Hostname</label>
        <div class="col-sm-4">
            <input type="text" value="{{ $data->mail_host }}" name="smtp_host_name" id="smtp_host_name" class="form-control">
        </div>
        <label class="col-sm-2 col-form-label" for="smtp_port">SMTP Port</label>
        <div class="col-sm-2">
            <input type="number" name="smtp_port" min="1" max="99999" value="{{ $data->mail_port }}" id="smtp_port" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="smtp_username">SMTP Username</label>
        <div class="col-sm-4">
            <div class="input-group">
                <input value="{{ $data->mail_user }}" type="password" class="form-control" name="smtp_username" id="smtp_username">
                <div class="input-group-append">
                    <button onclick="$('#smtp_username').attr('type')=='text' ? $('#smtp_username').attr('type','password') : $('#smtp_username').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
                </div>
            </div>
        </div>
        <label class="col-sm-2 col-form-label" for="smtp_password">SMTP Password</label>
        <div class="col-sm-4">
            <div class="input-group">
                <input value="{{ $data->mail_pass }}" type="password" class="form-control" name="smtp_password" id="smtp_password">
                <div class="input-group-append">
                    <button onclick="$('#smtp_password').attr('type')=='text' ? $('#smtp_password').attr('type','password') : $('#smtp_password').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="email_tujuan">Email Tujuan</label>
        <div class="col-sm-4">
            <input type="email" name="email_tujuan" id="email_tujuan" placeholder="Isi jika ingin mengetes kirim email" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="nama_pengirim">Nama Pengirim Email</label>
        <div class="col-sm-4">
            <input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Isi jika ingin mengetes kirim email" class="form-control">
        </div>
        <label class="col-sm-2 col-form-label" for="email_pengirim">Email Pengirim</label>
        <div class="col-sm-4">
            <input type="email" name="email_pengirim" id="email_pengirim" class="form-control" placeholder="Isi jika ingin mengetes kirim email">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="judul_email">Judul Email</label>
        <div class="col-sm-10">
            <input type="text" id="judul_email" name="judul_email" class="form-control" placeholder="Isi jika ingin mengetes kirim email">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="isi_email">Isi Email</label>
        <div class="col-sm-10">
            <textarea name="isi_email" id="isi_email" class="form-control" placeholder="Isi jika ingin mengetes kirim email"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10 offset-2">
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-floppy-p"></i> Simpan</button>
            <button type="button" onclick="testMail();return false" class="btn btn-outline-primary btn-send"><i class="fa fa-send"></i> Test Kirim Email</button>
        </div>
    </div>
</form>

<script>
    $('#form-mail-setting').submit(function () {
        var btnTest = $('#form-mail-setting .btn-send').html();
        $('#form-mail-setting .btn-submit').prop({'disabled':true}).html(btnSaveLoad);
        $('#form-mail-setting .btn-send').prop({'disabled':true}).html('<i class="fa fa-spin fa-circle-o-notch"></i> Test Kirim Email');
        $.ajax({
            url     : '{{ url('setting/email') }}',
            type    : 'POST',
            dataType: 'JSON',
            data    : $(this).serialize(),
            error   : function (e) {
                showError(parseError(e));
                $('#form-mail-setting .btn-submit').prop({'disabled':false}).html(btnSave);
                $('#form-mail-setting .btn-send').prop({'disabled':false}).html(btnTest);
            },
            success : function (e) {
                if (e.code === 1000){
                    showSuccess(e.msg);
                    $('#form-mail-setting .btn-submit').prop({'disabled':false}).html(btnSave);
                    $('#form-mail-setting .btn-send').prop({'disabled':false}).html(btnTest);
                }
            }
        });
        return false;
    });
    function testMail(){
        var btnTest = $('#form-mail-setting .btn-send').html();
        $('#form-mail-setting .btn-submit').prop({'disabled':true}).html(btnSaveLoad);
        $('#form-mail-setting .btn-send').prop({'disabled':true}).html('<i class="fa fa-spin fa-circle-o-notch"></i> Test Kirim Email');
        $.ajax({
            url     : '{{ url('setting/email-test') }}',
            type    : 'POST',
            dataType: 'JSON',
            data    : $('#form-mail-setting').serialize(),
            error   : function (e) {
                showError(parseError(e));
                $('#form-mail-setting .btn-submit').prop({'disabled':false}).html(btnSave);
                $('#form-mail-setting .btn-send').prop({'disabled':false}).html(btnTest);
            },
            success : function (e) {
                if (e.code === 1000){
                    showSuccess(e.msg);
                    $('#form-mail-setting .btn-submit').prop({'disabled':false}).html(btnSave);
                    $('#form-mail-setting .btn-send').prop({'disabled':false}).html(btnTest);
                }
            }
        });
    }
</script>