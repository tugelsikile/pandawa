function Kabupaten(regencyID){
    $('#nama_kabupaten').html('<option value=""></option>');
    var province_id = $('#nama_provinsi').val();
    $.ajax({
        url         : '/api/regional/kabupaten',
        type        : 'POST',
        dataType    : 'JSON',
        data        : { province_id : province_id },
        error       : function (e) {
            $('#nama_kabupaten,#nama_kecamatan,#nama_desa').html('<option value="">' + e.statusText + '</option>');
        },
        success     : function (e) {
            if (e.code < 1000){
                $('#nama_kabupaten').html('<option value="">' + e.msg + '</option>');
            } else {
                $('#nama_kabupaten').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == regencyID){
                        $('#nama_kabupaten').append('<option selected value="' + v.id + '">' + v.name + '</option>');
                    } else {
                        $('#nama_kabupaten').append('<option value="' + v.id + '">' + v.name + '</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('#nama_kabupaten').trigger('change');
                    }
                });
            }
        }
    });
}
function Kecamatan(districtID){
    $('#nama_kecamatan').html('<option value=""></option>');
    var regency_id = $('#nama_kabupaten').val();
    $.ajax({
        url         : '/api/regional/kecamatan',
        type        : 'POST',
        dataType    : 'JSON',
        data        : { regency_id : regency_id },
        error       : function (e) {
            $('#nama_kecamatan,#nama_desa').html('<option value="">' + e.statusText + '</option>');
        },
        success     : function (e) {
            if (e.code < 1000){
                $('#nama_kecamatan').html('<option value="">' + e.msg + '</option>');
            } else {
                $('#nama_kecamatan').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == districtID){
                        $('#nama_kecamatan').append('<option selected value="' + v.id + '">' + v.name + '</option>');
                    } else {
                        $('#nama_kecamatan').append('<option value="' + v.id + '">' + v.name + '</option>');
                    }
                    if (i + 1 >= e.params.length){
                        $('#nama_kecamatan').trigger('change');
                    }
                });
            }
        }
    });
}
function Desa(villageID){
    $('#nama_desa').html('<option value=""></option>');
    var district_id = $('#nama_kecamatan').val();
    $.ajax({
        url         : '/api/regional/desa',
        type        : 'POST',
        dataType    : 'JSON',
        data        : { district_id : district_id },
        error       : function (e) {
            $('#nama_desa').html('<option value="">' + e.statusText + '</option>');
        },
        success     : function (e) {
            if (e.code < 1000){
                $('#nama_desa').html('<option value="">' + e.msg + '</option>');
            } else {
                $('#nama_desa').html('');
                $.each(e.params,function (i,v) {
                    if (v.id == villageID){
                        $('#nama_desa').append('<option selected value="' + v.id + '">' + v.name + '</option>');
                    } else {
                        $('#nama_desa').append('<option value="' + v.id + '">' + v.name + '</option>');
                    }
                });
            }
        }
    });
}