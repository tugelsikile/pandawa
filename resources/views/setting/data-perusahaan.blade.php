<form id="ModalForm" onsubmit="submitForm(this)" method="post" action="{{ url('setting/data-perusahaan') }}">
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
            <select id="nama_kecamatan" name="nama_kecamatan" class="form-control"></select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="nama_kabupaten">Kabupaten</label>
        <div class="col-sm-4">
            <select name="nama_kabupaten" id="nama_kabupaten" class="form-control"></select>
        </div>
        <label class="col-sm-2 col-form-label" for="nama_provinsi">Provinsi</label>
        <div class="col-sm-4">
            <select name="nama_provinsi" id="nama_provinsi" class="form-control">
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>