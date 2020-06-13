<div id="template-email-invoice">
    <div id="data-email-template-invoice">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Template</label>
            <div class="col-sm-10">
                <div class="form-control">Template Invoice</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Judul Email</label>
            <div class="col-sm-10">
                <div class="form-control invoice_judul_email">{{ $data[1]->mail_subject }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control invoice_nama_pengirim">{{ $data[1]->sender_name }}</div>
            </div>
            <label class="col-sm-2 col-form-label">Email Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control invoice_email_pengirim">{{ $data[1]->mail_sender }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Body Email</label>
            <div class="col-sm-10">
                <div class="card"><div class="card-body invoice_body_email">{!! $data[1]->mail_body !!}</div> </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-2">
                <a href="javascript:;" class="btn btn-primary" onclick="$('#form-email-template-invoice').show();$('#data-email-template-invoice').hide();$('#template-email-password').hide();"><i class="fa fa-pencil"></i> Rubah Template Ini !</a>
            </div>
        </div>
    </div>
    <form id="form-email-template-invoice">
        @csrf
        <input type="hidden" name="data_template" value="{{ $data[1]->tmp_id }}">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_judul_email">Judul Email</label>
            <div class="col-sm-10">
                <input type="text" value="{{ $data[1]->mail_subject }}" name="judul_email" id="invoice_judul_email" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_nama_pengirim">Nama Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[1]->sender_name }}" name="nama_pengirim" id="invoice_nama_pengirim" class="form-control">
            </div>
            <label class="col-sm-2 col-form-label" for="invoice_email_pengirim">Email Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[1]->mail_sender }}" name="email_pengirim" id="invoice_email_pengirim" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_body_email">Body Email</label>
            <div class="col-sm-10">
                <textarea name="body_email" id="invoice_body_email" class="form-control">{{ $data[1]->mail_body }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-2">
                <button type="submit" class="btn btn-submit btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                <button type="button" onclick="$('#data-email-template-invoice').show();$('#form-email-template-invoice').hide();$('#template-email-password').show();" class="btn btn-cancel btn-secondary"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
    </form>
    <script>
        $('#form-email-template-invoice').hide();
        $('#invoice_body_email').summernote();
        $('#form-email-template-invoice').submit(function () {
            $('#form-email-template-invoice .btn').prop({'disabled':true});
            $('#form-email-template-invoice .btn-submit').html(btnSaveLoad);
            $('#form-email-template-invoice .btn-cancel').html(btnCloseLoad);
            $.ajax({
                url     : '{{ url('setting/template-email') }}',
                type    : 'POST',
                dataType: 'JSON',
                data    : $(this).serialize(),
                error   : function (e) {
                    showError(parseError(e));
                    $('#form-email-template-invoice .btn').prop({'disabled':false});
                    $('#form-email-template-invoice .btn-submit').html(btnSave);
                    $('#form-email-template-invoice .btn-cancel').html(btnClose);
                },
                success : function (e) {
                    console.log(e.params.request)
                    if (e.code === 1000){
                        showSuccess(e.msg);
                        $('.invoice_body_email').html(e.params.mail_body);
                        $('#invoice_body_email').val(e.params.mail_body);
                        $('.invoice_email_pengirim').html(e.params.mail_sender);
                        $('#invoice_email_pengirim').val(e.params.mail_sender);
                        $('.invoice_nama_pengirim').html(e.params.sender_name);
                        $('#invoice_nama_pengirim').val(e.params.sender_name);
                        $('.invoice_judul_email').html(e.params.mail_subject);
                        $('#invoice_judul_email').val(e.params.mail_subject);
                        $('#form-email-template-invoice .btn').prop({'disabled':false});
                        $('#form-email-template-invoice .btn-submit').html(btnSave);
                        $('#form-email-template-invoice .btn-cancel').html(btnClose);
                        $('#form-email-template-invoice').hide();
                        $('#data-email-template-invoice').show();
                        $('#template-email-password').show();
                    }
                }
            })
            return false;
        });
    </script>
</div>

<hr>

<div id="template-email-password">
    <div id="data-email-template-password">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Template</label>
            <div class="col-sm-10">
                <div class="form-control">Template Password</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Judul Email</label>
            <div class="col-sm-10">
                <div class="form-control password_judul_email">{{ $data[0]->mail_subject }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control password_nama_pengirim">{{ $data[0]->sender_name }}</div>
            </div>
            <label class="col-sm-2 col-form-label">Email Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control password_email_pengirim">{{ $data[0]->mail_sender }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Body Email</label>
            <div class="col-sm-10">
                <div class="card"><div class="card-body password_body_email">{!! $data[0]->mail_body !!}</div> </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-2">
                <a href="javascript:;" class="btn btn-primary" onclick="$('#form-email-template-password').show();$('#data-email-template-password').hide();$('#template-email-invoice').hide();"><i class="fa fa-pencil"></i> Rubah Template Ini !</a>
            </div>
        </div>
    </div>
    <form id="form-email-template-password">
        @csrf
        <input type="hidden" name="data_template" value="{{ $data[0]->tmp_id }}">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="password_judul_email">Judul Email</label>
            <div class="col-sm-10">
                <input type="text" value="{{ $data[0]->mail_subject }}" name="judul_email" id="password_judul_email" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="password_nama_pengirim">Nama Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[0]->sender_name }}" name="nama_pengirim" id="password_nama_pengirim" class="form-control">
            </div>
            <label class="col-sm-2 col-form-label" for="password_email_pengirim">Email Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[0]->mail_sender }}" name="email_pengirim" id="password_email_pengirim" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="password_body_email">Body Email</label>
            <div class="col-sm-10">
                <textarea name="body_email" id="password_body_email" class="form-control">{{ $data[0]->mail_body }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-2">
                <button type="submit" class="btn btn-submit btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                <button type="button" onclick="$('#data-email-template-password').show();$('#form-email-template-password').hide();$('#template-email-invoice').show();" class="btn btn-cancel btn-secondary"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
    </form>

    <script>
        $('#form-email-template-password').hide();
        $('#password_body_email').summernote();
        $('#form-email-template-password').submit(function () {
            $('#form-email-template-password .btn').prop({'disabled':true});
            $('#form-email-template-password .btn-submit').html(btnSaveLoad);
            $('#form-email-template-password .btn-cancel').html(btnCloseLoad);
            $.ajax({
                url     : '{{ url('setting/template-email') }}',
                type    : 'POST',
                dataType: 'JSON',
                data    : $(this).serialize(),
                error   : function (e) {
                    showError(parseError(e));
                    $('#form-email-template-password .btn').prop({'disabled':false});
                    $('#form-email-template-password .btn-submit').html(btnSave);
                    $('#form-email-template-password .btn-cancel').html(btnClose);
                },
                success : function (e) {
                    console.log(e.params.request)
                    if (e.code === 1000){
                        showSuccess(e.msg);
                        $('.password_body_email').html(e.params.mail_body);
                        $('#password_body_email').val(e.params.mail_body);
                        $('.password_email_pengirim').html(e.params.mail_sender);
                        $('#password_email_pengirim').val(e.params.mail_sender);
                        $('.password_nama_pengirim').html(e.params.sender_name);
                        $('#password_nama_pengirim').val(e.params.sender_name);
                        $('.password_judul_email').html(e.params.mail_subject);
                        $('#password_judul_email').val(e.params.mail_subject);
                        $('#form-email-template-password .btn').prop({'disabled':false});
                        $('#form-email-template-password .btn-submit').html(btnSave);
                        $('#form-email-template-password .btn-cancel').html(btnClose);
                        $('#form-email-template-password').hide();
                        $('#data-email-template-password').show();
                        $('#template-email-invoice').show();
                    }
                }
            })
            return false;
        });
    </script>
</div>