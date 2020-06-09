<div id="tabel-data-perusahaan">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nama Perusahaan</label>
        <div class="col-sm-10">
            <div class="form-control">{{ companyInfo()->company_name01 }}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Alamat Perusahaan</label>
        <div class="col-sm-10">
            <div class="form-control">{{ companyInfo()->address_01 }}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Desa</label>
        <div class="col-sm-4">
            <div class="form-control">{{ dataDesa(companyInfo()->village_id)->name }}</div>
        </div>
        <label class="col-sm-2 col-form-label">Kecamatan</label>
        <div class="col-sm-4">
            <div class="form-control">{{ dataKec(companyInfo()->district_id)->name }}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kabupaten</label>
        <div class="col-sm-4">
            <div class="form-control">{{ dataKab(companyInfo()->regency_id)->name }}</div>
        </div>
        <label class="col-sm-2 col-form-label">Provinsi</label>
        <div class="col-sm-4">
            <div class="form-control">{{ dataProv(companyInfo()->province_id)->name }}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kode Pos</label>
        <div class="col-sm-2">
            <div class="form-control">{{ companyInfo()->postal_code }}</div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Alamat Email</label>
        <div class="col-sm-4">
            <div class="form-control">{{ companyInfo()->email }}</div>
        </div>
        <label class="col-sm-2 col-form-label" for="nomor_telepon">No. Telp</label>
        <div class="col-sm-4">
            <div class="form-control">{{ companyInfo()->phone }}</div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <a class="btn btn-primary float-right" href="javascript:;" onclick="$('#form-data-perusahaan').show();$('#tabel-data-perusahaan').hide()"><i class="fa fa-pencil"></i> Rubah Data</a>
        </div>
    </div>
</div>
<div id="form-data-perusahaan" style="display:none">
    <form id="ModalForm" method="post" action="{{ url('setting/data-perusahaan') }}">
        @csrf
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_perusahaan">Nama Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama_perusahaan" id="nama_perusahaan" value="{{ companyInfo()->company_name01 }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_perusahaan">Alamat Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="alamat_perusahaan" id="alamat_perusahaan" value="{{ companyInfo()->address_01 }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_desa">Desa</label>
            <div class="col-sm-4">
                <select id="nama_desa" name="nama_desa" class="form-control"></select>
            </div>
            <label class="col-sm-2 col-form-label" for="nama_kecamatan">Kecamatan</label>
            <div class="col-sm-4">
                <select id="nama_kecamatan" name="nama_kecamatan" class="form-control" onchange="Desa({{ companyInfo()->village_id }})"></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_kabupaten">Kabupaten</label>
            <div class="col-sm-4">
                <select name="nama_kabupaten" id="nama_kabupaten" class="form-control" onchange="Kecamatan({{ companyInfo()->district_id }})"></select>
            </div>
            <label class="col-sm-2 col-form-label" for="nama_provinsi">Provinsi</label>
            <div class="col-sm-4">
                <select name="nama_provinsi" id="nama_provinsi" class="form-control" onchange="Kabupaten({{ companyInfo()->regency_id }})">
                    @foreach($provinces as $province)
                        <option @if($province->id == 32) selected @endif value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kode_pos">Kode Pos</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="kode_pos" id="kode_pos" value="{{ companyInfo()->postal_code }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_email">Alamat Email</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="alamat_email" id="alamat_email" value="{{ companyInfo()->email }}">
            </div>
            <label class="col-sm-2 col-form-label" for="nomor_telepon">No. Telp</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" value="{{ companyInfo()->phone }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-floppy-o"></i> Simpan</button>
            </div>
        </div>
    </form>
    <script>
        $('#nama_provinsi').trigger('change');
        $('#nama_provinsi,#nama_kabupaten,#nama_kecamatan,#nama_desa').select2();
        $('#ModalForm').submit(function () {
            $(this).find('.btn-primary').prop({'disabled':true}).html(btnSaveLoad);
            $.ajax({
                url     : $(this).attr('action'),
                type    : $(this).attr('method'),
                dataType: 'JSON',
                data    : $(this).serialize(),
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
                },
                success : function (e) {
                    if (e.code != 1000){
                        showError(e.msg)
                    } else {
                        showSuccess(e.msg);
                        settingPage({'href':'{{ url('setting/data-perusahaan') }}'});
                    }
                }
            });
            return false;
        });
    </script>
</div>