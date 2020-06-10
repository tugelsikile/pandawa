<form onsubmit="submitFormNPWP(this);return false" id="FormNPWP" method="post" action="{{ url('setting/template-invoice-npwp') }}">
    @csrf
    <input type="hidden" name="idnya" value="{{ $data[0]->idnya }}">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="template_npwp">Template Invoice</label>
        <div class="col-sm-4">
            <input onkeyup="previewNomorInvoice($(this).val(),$('#panjang_template_npwp').val(),'preview_npwp')" onchange="previewNomorInvoice($(this).val(),$('#panjang_template_npwp').val(),'preview_npwp')" type="text" class="form-control" name="isi_template" id="template_npwp" value="{{ $data[0]->id_string }}">
        </div>
        <label class="col-sm-2 col-form-label" for="panjang_template_npwp">Panjang 0</label>
        <div class="col-sm-2">
            <input onchange="previewNomorInvoice($('#template_npwp').val(),$(this).val(),'preview_npwp')" type="number" class="form-control" name="panjang_nol" id="panjang_template_npwp" value="{{ $data[0]->str_pad }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Preview ID</label>
        <div class="col-sm-4">
            <input type="text" disabled id="preview_npwp" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-submit btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
            <button onclick="$('#FormNPWP').hide();$('#data-npwp').show();" type="button" class="btn btn-cancel btn-secondary"><i class="fa fa-close"></i> Tutup</button>
        </div>
    </div>
</form>
<div id="data-npwp">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nomor Invoice dengan NPWP</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" disabled id="npwp-template">
        </div>
        <div class="col-sm-1">
            <a href="javascript:;" onclick="$('#data-npwp').hide();$('#FormNPWP').show();" class="btn btn-block btn-primary"><i class="fa fa-pencil"></i></a>
        </div>
    </div>
</div>
<hr>
<form onsubmit="submitFormNPWP(this);return false" id="FormNonNPWP" method="post" action="{{ url('setting/template-invoice-non-npwp') }}">
    @csrf
    <input type="hidden" name="idnya" value="{{ $data[1]->idnya }}">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="template_non_npwp">Template Invoice</label>
        <div class="col-sm-4">
            <input onkeyup="previewNomorInvoice($(this).val(),$('#panjang_template_non_npwp').val(),'preview_non_npwp')" onchange="previewNomorInvoice($(this).val(),$('#panjang_template_non_npwp').val(),'preview_non_npwp')" type="text" class="form-control" name="isi_template" id="template_non_npwp" value="{{ $data[1]->id_string }}">
        </div>
        <label class="col-sm-2 col-form-label" for="panjang_template_non_npwp">Panjang 0</label>
        <div class="col-sm-2">
            <input onchange="previewNomorInvoice($('#template_non_npwp').val(),$(this).val(),'preview_non_npwp')" type="number" class="form-control" name="panjang_nol" id="panjang_template_non_npwp" value="{{ $data[1]->str_pad }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Preview ID</label>
        <div class="col-sm-4">
            <input type="text" disabled id="preview_non_npwp" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-submit btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
            <button onclick="$('#FormNonNPWP').hide();$('#data-non-npwp').show();" type="button" class="btn btn-cancel btn-secondary"><i class="fa fa-close"></i> Tutup</button>
        </div>
    </div>
</form>
<div id="data-non-npwp">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nomor Invoice tanpa NPWP</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" disabled id="non-npwp-template">
        </div>
        <div class="col-sm-1">
            <a href="javascript:;" onclick="$('#data-non-npwp').hide();$('#FormNonNPWP').show();" class="btn btn-block btn-primary"><i class="fa fa-pencil"></i></a>
        </div>
    </div>
</div>

<script>
    $('#FormNPWP,#FormNonNPWP').hide();
    previewNomorInvoice($('#template_npwp').val(),$('#panjang_template_npwp').val(),'npwp-template');
    previewNomorInvoice($('#template_npwp').val(),$('#panjang_template_npwp').val(),'preview_npwp');
    previewNomorInvoice($('#template_non_npwp').val(),$('#panjang_template_non_npwp').val(),'preview_non_npwp');
    previewNomorInvoice($('#template_non_npwp').val(),$('#panjang_template_non_npwp').val(),'non-npwp-template');
    function submitFormNPWP(obj) {
        $(obj).find('.btn').prop({'disabled':true});
        $(obj).find('.btn-submit').html(btnSaveLoad);
        $(obj).find('.btn-cancel').html(btnCloseLoad);
        $.ajax({
            url     : '{{ url('setting/template-update') }}',
            type    : 'POST',
            dataType: 'JSON',
            data    : $(obj).serialize(),
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
                $(obj).find('.btn').prop({'disabled':false});
                $(obj).find('.btn-submit').html(btnSave);
                $(obj).find('.btn-cancel').html(btnClose);
            },
            success : function (e) {
                $(obj).find('.btn').prop({'disabled':false});
                $(obj).find('.btn-submit').html(btnSave);
                $(obj).find('.btn-cancel').html(btnClose);
                showSuccess(e.msg);
                settingPage({'href':'{{ url('setting/template-invoice') }}'})
            }
        });
        return false;
    }
</script>