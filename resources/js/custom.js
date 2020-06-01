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
                $('#MyModal').modal('hide');
                showSuccess(e.msg);
            } else {
                showError(e.msg);
            }
            $('#MyModal .modal-footer .btn-submit').prop({'disabled':false}).html(btnSave);
            $('#MyModal .modal-footer .btn-close').prop({'disabled':false}).html(btnClose);
        }
    });
}
function delete_data(obj) {
    var id      = $(obj).attr('data-id');
    var title   = $(obj).attr('title');
    var url     = $(obj).attr('href');
    var token   = $(obj).attr('data-token');
    Swal.fire({
        title               : title+'?',
        text                : 'Data yang bersangkutan akan dihapus juga',
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
                data    : { _token : token, id : id },
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
}
function bulk_delete(obj) {
    var title   = $(obj).attr('title');
    var url     = $(obj).attr('href');
    var token   = $(obj).attr('data-token');
    var dataLength = $('#dataTable tbody input:checkbox:checked').length;
    if (dataLength == 0){
        showError('Pilih data yang akan dihapus lebih dulu');
    } else {
        Swal.fire({
            title               : title+'?',
            text                : 'Data yang bersangkutan akan dihapus juga',
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
                    data    : $('#FormTable').serialize()+'&_token='+token,
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
    }
}
function kodeProduk() {
    var cab_id  = $('#nama_cabang').val();
    if (cab_id.length == 0){
        $('#kode_produk').val('');
    } else {
        $.ajax({
            url     : '/admin-produk/kode-produk',
            type    : 'POST',
            dataType: 'JSON',
            data    : { cab_id : cab_id },
            error   : function (e) {
                $('#kode_produk').val('');
            },
            success : function (e) {
                $('#kode_produk').val(e.params);
            }
        });
    }
}
function previewHarga() {
    var price   = $('#harga_produk').val();
    var tax     = $('#pajak_produk').val();
    $('#preview_harga').val('Loading...');
    $.ajax({
        url     : '/preview-harga',
        type    : 'POST',
        dataType: 'JSON',
        data    : { price : price, tax : tax },
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
            $('#preview_harga').val(e.statusText+' '+msg);
        },
        success : function (e) {
            $('#preview_harga').val(e.params);
        }
    });
}
function tableCbxAll(obj) {
    $('#dataTable tbody input:checkbox').prop({'checked':$(obj).prop('checked')});
}
$(document).on('hidden.bs.modal','#MyModal', function () {
    $('#MyModal').remove();
});
function showError(msg) {
    $.notify({
        title   : 'Error',
        icon    : 'fa fa-exclamation-triangle',
        message : msg
    },{
        type    : 'danger',
        z_index : 99999,
        animate : {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        }
    });
}
function showSuccess(msg) {
    $.notify({
        title   : 'Sukses',
        icon    : 'fa fa-check-circle',
        message : msg
    },{
        type    : 'success',
        z_index : 99999,
        animate : {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        }
    });
}
function showInfo(msg) {
    $.notify({
        title   : 'Informasi',
        icon    : 'fa fa-info-circle',
        message : msg
    },{
        type    : 'info',
        z_index : 99999,
        animate : {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        }
    });
}
function ucWords(str) {
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
}
function getKab(obj,defaultRegencyID){
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
                    if (v.id == '3212' || v.id == defaultRegencyID){
                        $('.regency_id').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('.regency_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('.regency_id').trigger('change');
                    }
                });
            }
        }
    });
}
function getKec(obj,defaultDistrictID) {
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
                    if (v.id == defaultDistrictID){
                        $('.district_id').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('.district_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('.district_id').trigger('change');
                    }
                });
            }
        }
    });
}
function getDesa(obj,defaultDesaID) {
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
                    if (v.id == defaultDesaID){
                        $('.village_id').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('.village_id').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    }
                });
            }
        }
    });
}
function previewID() {
    var template    = $('#template').val();
    var padding     = $('#pad').val();
    $('#preview_id').val('Loading...');
    $.ajax({
        url     : '/preview-id',
        type    : 'POST',
        dataType: 'JSON',
        data    : { template : template, padding : padding },
        error   : function (e) {
            $('#preview_id').val(e.statusText);
        },
        success : function (e) {
            if (e.code == 1000){
                $('#preview_id').val(e.params);
            } else {
                $('#preview_id').val('Undefined');
            }
        }
    });
}