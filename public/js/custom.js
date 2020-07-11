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
            '<div id="MyModal" class="modal" tabindex="-1" role="dialog">\n' +
                '<div class="modal-dialog modal-xl" role="document">\n' +
                    '<form onsubmit="submitForm(this);return false" id="ModalForm" method="post" action="'+url+'">\n' +
                        '<div class="modal-content">\n' +
                            '<div class="modal-header">\n' +
                                '<h5 class="modal-title">'+ title +'</h5>\n' +
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                                    '<span aria-hidden="true">&times;</span>\n' +
                                '</button>\n' +
                            '</div>\n' +
                            '<div class="modal-body"><div class="text-center">Loading...</div></div>\n' +
                            '<div class="modal-footer">\n' +
                                '<button type="submit" class="btn-submit btn btn-primary">'+ btnSave +'</button>\n' +
                                '<button type="button" class="btn-close btn btn-secondary" data-dismiss="modal">'+ btnClose +'</button>\n' +
                            '</div>\n' +
                        '</div>\n' +
                    '</form>\n' +
                '</div>\n' +
            '</div>';
        $('body').append(html);
        $('#MyModal').modal('show');
        $('#MyModal .modal-content .modal-body').load(url,function (a,b,c){
            if (b == 'error'){
                $('#MyModal .modal-content .modal-body').html('Error '+c.status+' '+c.statusText+'<br>'+c.responseText);
                $('#MyModal .modal-footer').remove();
            }
        });
    }
}
function parseError(jsonstring) {
    var msg = '';
    var jsonResponse = jsonstring.responseJSON;
    if (jsonResponse){
        jsonResponse = jsonResponse.message;
        jsonResponse = jsonResponse.split('#');
        msg = '<ul>';
        $.each(jsonResponse,function (i,v) {
            msg += '<li>'+v+'</li>';
        });
        msg += '</ul>';
    }
    return msg;
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
            var msg = parseError(e);
            showError(msg);
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
function listCustomer() {
    if ($('#nama_cabang')){
        var cabang_id   = $('#nama_cabang').val();
        var msg         = '';
        $.ajax({
            url     : APP_URL + '/api/lists/customers',
            type    : 'POST',
            dataType: 'JSON',
            data    : { nama_cabang : cabang_id },
            error   : function (e) {
                var jsonResponse = e.responseJSON;
                if (jsonResponse) msg = jsonResponse.message;
                $('#nama_pelanggan').html('<option value="">'+ msg +'</option>');
            },
            success : function (e) {
                if (e.code == 1000){
                    $('#nama_pelanggan').html('<option value="">=== Nama Pelanggan ===</option>');
                    $.each(e.params,function (i,v) {
                        $('#nama_pelanggan').append('<option value="' + v.cust_id + '">' + v.fullname + '</option>');
                        if (i + 1 >= e.params.length){
                            if ($('#nama_produk')){
                                listProduk();
                            }
                        }
                    });
                }
            }
        });
    }
}
function listProduk() {
    if ($('#nama_cabang') && $('#nama_produk')){
        var cabang_id   = $('#nama_cabang').val();
        var msg         = '';
        $.ajax({
            url     : APP_URL + '/api/lists/produk-cabang',
            dataType: 'JSON',
            type    : 'POST',
            data    : { cab_id : cabang_id },
            error   : function (e) {
                var jsonResponse = e.responseJSON;
                if (jsonResponse) msg = jsonResponse.message;
                $('#nama_produk').html('<option value="">'+ msg +'</option>');
            },
            success : function (e) {
                if (e.code == 1000){
                    $('#nama_produk').html('<option value="">=== Nama Produk ===</option>');
                    $.each(e.params,function (i,v) {
                        $('#nama_produk').append('<option value="' + v.pac_id + '">' + v.pac_name + ' ' + v.price_format + '</option>');
                    });
                }
            }
        });
    }
}
function setStatusAktif(obj) {
    var id      = $(obj).attr('data-id');
    var status  = $(obj).attr('data-value');
    var title   = $(obj).attr('title');
    var url     = $(obj).attr('href');
    var token   = $(obj).attr('data-token');
    Swal.fire({
        title               : title,
        text                : 'Anda yakin ?',
        icon                : 'warning',
        showCancelButton    : true,
        confirmButtonColor  : '#3085d6',
        cancelButtonColor   : '#d33',
        confirmButtonText   : 'Konfirmasi',
        cancelButtonText    : 'Batal',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url     : url,
                type    : 'POST',
                dataType: 'JSON',
                data    : { _token : token, id : id, data_status : status },
                error   : function (e) {
                    var msg = parseError(e);
                    showError(msg);
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
                    var msg = parseError(e)
                    showError(msg);
                },
                success : function (e) {
                    if (e.code == 1000){
                        if (typeof table !== 'undefined'){
                            table._fnDraw(false);
                        }
                        if ($('#MyModal').length > 0){
                            $('#MyModal').modal('hide');
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
                        var msg = parseError(e);
                        showError(msg);
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
function getProdukCabang(obj){
    var cab_id  = $(obj).val();
    if (cab_id.length == 0){
        $('#nama_produk').html('');
    } else {
        $('#nama_produk').html('<option value="">Loading...</option>');
        $.ajax({
            url     : APP_URL + '/lists/produk-cabang',
            type    : 'POST',
            dataType: 'JSON',
            data    : { cab_id : cab_id },
            error   : function (e) {
                $('#nama_produk').html('<option value="">'+e.statusText+'</option>');
            },
            success : function (e) {
                if (e.params.length > 0){
                    $('#nama_produk').html('');
                    $.each(e.params,function (i,v) {
                        $('#nama_produk').append('<option value="'+v.pac_id+'">'+v.pac_name+' | '+v.price_format+'</option>');
                    });
                }
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
            url     : APP_URL + '/admin-produk/kode-produk',
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
function kodeCustomer() {
    var cab_id  = $('#nama_cabang').val();
    if (cab_id.length == 0){
        $('#nomor_pelanggan,#nomor_pelanggan_text').val('');
    } else {
        $.ajax({
            url     : APP_URL + '/preview-id-pelanggan',
            type    : 'POST',
            dataType: 'JSON',
            data    : { cab_id : cab_id },
            error   : function (e) {
                $('#nomor_pelanggan,#nomor_pelanggan_text').val('');
            },
            success : function (e) {
                $('#nomor_pelanggan,#nomor_pelanggan_text').val(e.params);
            }
        })
    }
}
function previewHarga() {
    var price   = $('#harga_produk').val();
    var tax     = $('#pajak_produk').val();
    $('#preview_harga').val('Loading...');
    $.ajax({
        url     : APP_URL + '/preview-harga',
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
        onShow  : function () {
            $('*.toast').css({'opacity':1});
        },
        type    : 'danger',
        z_index : 99999,
        animate : {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        template:   '<div data-notify="container" class="toast toast-{0}" role="alert" aria-live="assertive" aria-atomic="true">\n' +
                        '<div class="toast-header">\n' +
                            '<strong class="mr-auto">{1}</strong>\n' +
                            '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">\n' +
                                '<span aria-hidden="true">&times;</span>\n' +
                            '</button>\n' +
                        '</div>\n' +
                        '<div class="toast-body">{2}</div>\n' +
                    '</div>'
    });
}
function showSuccess(msg) {
    $.notify({
        title   : 'Sukses',
        icon    : 'fa fa-check-circle',
        message : msg
    },{
        onShow  : function () {
            $('*.toast').css({'opacity':1});
        },
        type    : 'success',
        z_index : 99999,
        animate : {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        template:   '<div data-notify="container" class="toast toast-{0}" role="alert" aria-live="assertive" aria-atomic="true">\n' +
                        '<div class="toast-header">\n' +
                            '<span data-notify="icon"></span>\n' +
                            '<strong data-notify="title" class="mr-auto">{1}</strong>\n' +
                            '<button data-notify="dismiss" type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">\n' +
                                '<span aria-hidden="true">&times;</span>\n' +
                            '</button>\n' +
                        '</div>\n' +
                        '<div data-notify="message" class="toast-body">{2}</div>\n' +
                    '</div>'
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
        },
        template:   '<div data-notify="container" class="toast toast-{0}" role="alert" aria-live="assertive" aria-atomic="true">\n' +
                        '<div class="toast-header">\n' +
                            '<span data-notify="icon"></span>\n' +
                            '<strong data-notify="title" class="mr-auto">{1}</strong>\n' +
                            '<button data-notify="dismiss" type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">\n' +
                                '<span aria-hidden="true">&times;</span>\n' +
                            '</button>\n' +
                        '</div>\n' +
                        '<div data-notify="message" class="toast-body">{2}</div>\n' +
                    '</div>'
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
        url     : APP_URL + '/regional/get-kab',
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
                    if (v.id == defaultRegencyID){
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
function getKabPenagihan(kabID) {
    var prov_id = $('#provinsi_penagihan').val();
    $('#kabupaten_penagihan,#kecamatan_penagihan,#desa_penagihan').html('<option value="">Loading...</option>');
    $.ajax({
        url     : APP_URL + '/regional/get-kab',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : prov_id },
        error   : function (e) {
            $('#kabupaten_penagihan,#kecamatan_penagihan,#desa_penagihan').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('#kabupaten_penagihan,#kecamatan_penagihan,#desa_penagihan').html('<option value="">'+e.msg+'</option>');
            } else {
                $('#kabupaten_penagihan').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == kabID){
                        $('#kabupaten_penagihan').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('#kabupaten_penagihan').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('#kabupaten_penagihan').trigger('change');
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
        url     : APP_URL + '/regional/get-kec',
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
function getKecPenagihan(kecID){
    var kab_id = $('#kabupaten_penagihan').val();
    $('#kecamatan_penagihan,#desa_penagihan').html('<option value="">Loading...</option>');
    $.ajax({
        url     : APP_URL + '/regional/get-kec',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : kab_id },
        error   : function (e) {
            $('#kecamatan_penagihan,#desa_penagihan').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('#kecamatan_penagihan,#desa_penagihan').html('<option value="">'+e.msg+'</option>');
            } else {
                $('#kecamatan_penagihan').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == kecID){
                        $('#kecamatan_penagihan').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('#kecamatan_penagihan').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('#kecamatan_penagihan').trigger('change');
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
        url     : APP_URL + '/regional/get-desa',
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
function getDesaPenagihan(desaID){
    var kec_id = $('#kecamatan_penagihan').val();
    $('#desa_penagihan').html('<option value="">Loading...</option>');
    $.ajax({
        url     : APP_URL + '/regional/get-desa',
        type    : 'GET',
        dataType: 'JSON',
        data    : { id : kec_id },
        error   : function (e) {
            $('#desa_penagihan').html('<option value="">'+e.statusText+'</option>');
        },
        success : function (e) {
            if (e.code < 1000){
                $('#desa_penagihan').html('<option value="">'+e.msg+'</option>');
            } else {
                $('#desa_penagihan').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == desaID){
                        $('#desa_penagihan').append('<option selected value="'+v.id+'">'+ucWords(v.name)+'</option>');
                    } else {
                        $('#desa_penagihan').append('<option value="'+v.id+'">'+ucWords(v.name)+'</option>');
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
        url     : APP_URL + '/preview-id',
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
function printNow() {
    if ($('.is-print').is(':visible')){
        if ($('#printFrame')){
            window.frames["printFrame"].focus();
            window.frames["printFrame"].print();
        }
    }
}
function printCancel() {
    if ($('.is-print')){ if ($('.is-print').is(':visible')){ $('.is-print').hide(); } }
    if ($('.no-print')){ if ($('.no-print').is(':hidden')){ $('.no-print').show(); }}
    if ($('#printFrame')){ $('#printFrame').attr({'src':'/api/cetak-loading'}); }
}
function printDataPost(obj) {
    var formElement     = $('#'+$(obj).attr('data-form'));
    var iframeElement   = $('#'+$(obj).attr('data-frame'));
    if ($(formElement) && $(iframeElement)){
        formElement.attr({'target':$(obj).attr('data-frame'),'method':'POST','action':$(obj).attr('href')});
        if ($('.is-print')){ if ($('.is-print').is(':hidden')){$('.is-print').show()}}
        if ($('.no-print')){if($('.no-print').is(':visible')){$('.no-print').hide()}}
        formElement.submit();
    }
}
function printData(obj) {
    var url     = $(obj).attr('href');
    if (url){
        if (url.length > 0){
            if ($('#printFrame')){ $('#printFrame').attr({'src':url}); }
            if ($('.is-print')){ if ($('.is-print').is(':hidden')){$('.is-print').show()}}
            if ($('.no-print')){if($('.no-print').is(':visible')){$('.no-print').hide()}}
        }
    }
}
function settingPage(obj){
    var url = $(obj).attr('href');
    if (url.length>0 && $('#setting-container').length>0){
        $('#setting-container').html('Loading ...');
        $('#setting-container').load(url,function () {

        });
    }
}
function tagihanInformasi(url,token,bulan,tahun,cabang,npwp,active,paid,mitra,jenis){
    if (tahun == 'undefined') tahun = null;
    if (bulan == 'undefined') bulan = null;
    $('.tagihan-total,.tagihan-dibayar,.tagihan-tunggak').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading...');
    $.ajax({
        url     : url,
        type    : 'POST',
        dataType: 'JSON',
        data    : { jenis : jenis,_token : token, bulan : bulan, tahun : tahun, cab_id : cabang, npwp : npwp, is_active : active, paid : paid, mitra : mitra },
        error   : function (e) {
            $('.tagihan-total,.tagihan-dibayar,.tagihan-tunggak').html('Eyoy bos');
        },
        success : function (e) {
            $('.tagihan-total').html(e.params.total);
            $('.tagihan-dibayar').html(e.params.dibayar);
            $('.tagihan-tunggak').html(e.params.tunggak);
        }
    })
}
function bulanIndo(string) {
    if (string != null){
        var arr = string.split("-");
        var months = [ "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December" ];
        var month_index =  parseInt(arr[1],10) - 1;
        return months[month_index];
    }
}
function tanggalIndo(string) {
    if (string != null){
        var arr = string.split('-');
        return arr[2];
    }
}
function tahunIndo(string) {
    if (string != null){
        var arr = string.split('-');
        return arr[0];
    }
}
function cari_mitra() {
    var mitra   = $('.mitra').val();
    $.ajax({
        url     : APP_URL + '/lists/cabang',
        type    : 'POST',
        dataType: 'JSON',
        data    : { mitra : mitra, _token : csrf_token },
        error   : function (e) {
            $('.cab-id').html('<option value="">Error</option>');
            table._fnDraw();
        },
        success : function (e) {
            $('.cab-id').html('<option value="">=== Semua Cabang / Mitra ===</option>');
            $.each(e.params,function (i,v) {
                $('.cab-id').append('<option value="'+v.cab_id+'">'+v.cab_name+'</option>');
            });
            table._fnDraw();
        }
    })
}