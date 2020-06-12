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
                <div class="form-control">{{ $data[0]->mail_subject }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control">{{ $data[0]->sender_name }}</div>
            </div>
            <label class="col-sm-2 col-form-label">Email Pengirim</label>
            <div class="col-sm-4">
                <div class="form-control">{{ $data[0]->mail_sender }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Body Email</label>
            <div class="col-sm-10">
                <div class="card"><div class="card-body">{!! $data[0]->mail_body !!}</div> </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-2">
                <a href="javascript:;" class="btn btn-primary" onclick="$('#form-email-template-invoice').show();$('#data-email-template-invoice').hide()"><i class="fa fa-pencil"></i> Rubah Template Ini !</a>
            </div>
        </div>
    </div>
    <form id="form-email-template-invoice">
        @csrf
        <input type="hidden" name="data_template" value="{{ $data[0]->tmp_id }}">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_judul_email">Judul Email</label>
            <div class="col-sm-10">
                <input type="text" value="{{ $data[0]->mail_subject }}" name="judul_email" id="invoice_judul_email" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_nama_pengirim">Nama Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[0]->sender_name }}" name="nama_pengirim" id="invoice_nama_pengirim" class="form-control">
            </div>
            <label class="col-sm-2 col-form-label" for="invoice_email_pengirim">Email Pengirim</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data[0]->mail_sender }}" name="email_pengirim" id="invoice_email_pengirim" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="invoice_body_email">Body Email</label>
            <div class="col-sm-10">
                <textarea name="body_email" id="invoice_body_email" class="form-control">{{ $data[0]->mail_body }}</textarea>
            </div>
        </div>
    </form>
    <script>
        $('#invoice_body_email').summernote();
    </script>
</div>

<hr>