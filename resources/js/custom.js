$.fn.select2.defaults.set( "theme", "bootstrap" );
var btnSave = '<i class="fa fa-floppy-o"></i> Simpan';
var btnSaveLoad = '<i class="fa fa-spin fa-circle-o-notch"></i> Simpan';
var btnClose = '<i class="fa fa-close"></i> Tutup';
var btnCloseLoad = '<i class="fa fa-spin fa-close"></i> Tutup';
function show_modal(obj) {
    var title = !$(obj).attr('title') ? 'Form' : $(obj).attr('title');
    var url   = $(obj).attr('href');
    if (url){
        var html = '' +
            '<div id="MyModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">\n' +
            '  <form id="ModalForm" class="form form-horizontal" method="post" action="" onsubmit="submitForm(this);return false">' +
            '  <div class="modal-dialog modal-lg" role="document">\n' +
            '    <div class="modal-content">\n' +
            '      <div class="modal-header">\n' +
            '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
            '        <h4 class="modal-title">' + title + '</h4>\n' +
            '      </div>\n' +
            '      <div class="modal-body">\n' +
            '        <p>One fine body&hellip;</p>\n' +
            '      </div>\n' +
            '      <div class="modal-footer">\n' +
            '        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">'+ btnClose +'</button>\n' +
            '        <button type="submit" class="btn btn-primary btn-submit">'+ btnSave +'</button>\n' +
            '      </div>\n' +
            '    </div>\n' +
            '  </div>\n' +
            '  </form>' +
            '</div>';
        $('body').append(html);
        $('#MyModal .modal-content .modal-body').load(url,function (a,b,c){
            if (b == 'error'){
                $('#MyModal .modal-content .modal-body').html('Error '+c.status+' '+c.statusText+'<br>'+c.responseText);
                $('#MyModal .modal-footer').remove();
            }
            $('#MyModal').modal('show');
        });
    }
}
function submitForm(obj) {
    var method  = $(obj).attr('method');
    var url     = $(obj).attr('action');
    $('#MyModal .modal-footer .btn-submit').prop({'disabled':true}).html(btnSaveLoad);
    $('#MyModal .modal-footer .btn-close').prop({'disabled':true}).html(btnCloseLoad);
    $.ajax({
        url     : url,
        type    : method,
        dataType: 'JSON',
        data    : $(obj).serialize(),
        error   : function (e) {
            $('#MyModal .modal-footer .btn-submit').prop({'disabled':false}).html(btnSave);
            $('#MyModal .modal-footer .btn-close').prop({'disabled':false}).html(btnClose);
            showError(e.statusText);
        },
        success : function (e) {
            $('#MyModal .modal-footer .btn-submit').prop({'disabled':false}).html(btnSave);
            $('#MyModal .modal-footer .btn-close').prop({'disabled':false}).html(btnClose);
        }
    });
}
$(document).on('hidden.bs.modal','#MyModal', function () {
    $('#MyModal').remove();
});
function showError(msg) {
    $.notify({
        icon : '<i class="fa fa-exclamation-triangle"></i>',
        message: msg
    },{
        type : 'danger',
        z_index : 99999
    });
}
function showSuccess(msg) {
    $.notify({
        icon : '<i class="fa fa-check-circle"></i>',
        message: msg
    },{
        type : 'success',
        z_index : 99999
    });
}
function showInfo(msg) {
    $.notify({
        icon : '<i class="fa fa-info-circle"></i>',
        message: msg
    },{
        type : 'info',
        z_index : 99999
    });
}
function ucWords(str) {
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
}
function getKab(obj) {
    var prov_id = $(obj).val();
    $('.regency_id').html('<option value="">Loading...</option>');
    $.ajax({
        url     : '/regional/get-kab',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : prov_id },
        error   : function (e) {
            $('.regency_id,.district_id,.village_id').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('.regency_id,.district_id,.village_id').html('<option value="">'+e.msg+'</option>');
            } else {
                $('.regency_id').html('');
                $.each(e.params,function (i,v) {
                    $('.regency_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    if (i + 1 >= e.params.length){
                        $('.regency_id').trigger('change');
                    }
                });
            }
        }
    });
}
function getKec(obj) {
    var kab_id = $(obj).val();
    $('.district_id').html('<option value="">Loading...</option>');
    $.ajax({
        url     : '/regional/get-kec',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : kab_id },
        error   : function (e) {
            $('.district_id,.village_id').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('.district_id,.village_id').html('<option value="">'+e.msg+'</option>');
            } else {
                $('.district_id').html('');
                $.each(e.params,function (i,v) {
                    $('.district_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    if (i + 1 >= e.params.length){
                        $('.district_id').trigger('change');
                    }
                });
            }
        }
    });
}
function getDesa(obj) {
    var kab_id = $(obj).val();
    $('.village_id').html('<option value="">Loading...</option>');
    $.ajax({
        url     : '/regional/get-desa',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : kab_id },
        error   : function (e) {
            $('.village_id').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('.village_id').html('<option value="">'+e.msg+'</option>');
            } else {
                $('.village_id').html('');
                $.each(e.params,function (i,v) {
                    $('.village_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                });
            }
        }
    });
}