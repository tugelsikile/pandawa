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
            '  <form id="ModalForm" method="post" action="" onsubmit="submitForm(this);return false">' +
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