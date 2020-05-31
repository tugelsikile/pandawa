@csrf
<input type="hidden" name="cab_id" value="{{ $data->cab_id }}">
<div class="form-group">
    <label class="col-sm-2 control-label">Jenis Cabang</label>
    <div class="col-sm-4">
        <select name="mitra" class="form-control mitra" required>
            <option value="1" @if($data->mitra==1) selected @endif>Mitra</option>
            <option value="0" @if($data->mitra==0) selected @endif>Cabang</option>
        </select>
    </div>
    <label class="col-sm-2 control-label">Share %</label>
    <div class="col-sm-2">
        <input required type="number" value="{{ $data->share_percent }}" min="0" max="100" class="form-control" name="share_percent" id="share_percent">
    </div>
</div>
<div class="form-group">
    <label for="cab_name" class="col-sm-2 control-label">Nama Cabang</label>
    <div class="col-sm-10">
        <input name="cab_name" id="cab_name" type="text" class="cab_name form-control" required value="{{ $data->cab_name }}">
    </div>
</div>
<div class="form-group">
    <label for="alamat" class="col-sm-2 control-label">Alamat</label>
    <div class="col-sm-10">
        <textarea name="alamat" id="alamat" class="form-control">{{ $data->address02 }}</textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Desa / Kelurahan</label>
    <div class="col-sm-4">
        <select name="village_id" class="form-control village_id" style="width:100%" required>
            <option value="">=== Desa ===</option>
        </select>
    </div>
    <label class="col-sm-2 control-label">Kecamatan</label>
    <div class="col-sm-4">
        <select name="district_id" class="form-control district_id" style="width:100%;" onchange="getDesa(this,'{{ $village->id }}')" required>
            <option value="">=== Kecamatan ===</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Kab. / Kota</label>
    <div class="col-sm-4">
        <select name="regency_id" class="form-control regency_id" style="width:100%;" onchange="getKec(this,'{{ $district->id }}')" required>
            <option value="">=== Kabupaten ===</option>
        </select>
    </div>
    <label class="col-sm-2 control-label">Provinsi</label>
    <div class="col-sm-4">
        <select name="province_id" class="form-control province_id" style="width:100%;" onchange="getKab(this,'{{ $regency->id }}')" required>
            @if($prov)
                @foreach($prov as $key => $val)
                    <option @if($val->id == 32 || $val->id == $regency->province_id) selected @endif value="{{ $val->id }}">{{ ucwords(strtolower($val->name)) }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Kode Pos</label>
    <div class="col-sm-4">
        <input type="text" class="form-control kode_pos" name="kode_pos" id="kode_pos" value="{{ $data->postal }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">No. Telp / HP</label>
    <div class="col-sm-4">
        <input type="text" name="telp" id="telp" class="telp form-control" value="{{ $data->phone }}">
    </div>
    <label class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
        <input type="email" name="email" id="email" class="form-control email" value="{{ $data->email }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Nama Pemilik</label>
    <div class="col-sm-4">
        <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control nama_pemilik" value="{{ $data->owner }}">
    </div>
    <label class="col-sm-2 control-label">No. Telp. Pemilik</label>
    <div class="col-sm-4">
        <input type="text" name="telp_pemilik" id="telp_pemilik" class="form-control telp_pemilik" value="{{ $data->owner_phone }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">ID Pelanggan</label>
    <div class="col-sm-4">
        <input onchange="previewID()" onkeyup="previewID()" type="text" class="form-control template" id="template" name="template" required value="{{ $data->id_template }}">
    </div>
    <label class="col-sm-2 control-label">Panjang 0</label>
    <div class="col-sm-2">
        <input onchange="previewID()" onkeyup="previewID()" type="number" value="{{ $data->id_template_pad }}" max="10" min="2" class="form-control pad" id="pad" name="pad" required>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Preview ID</label>
    <div class="col-sm-4">
        <input type="text" id="preview_id" class="form-control preview_id" disabled value="">
    </div>
</div>
<script>
    getKab($('#ModalForm .province_id'));
    previewID();
    $('#ModalForm .mitra,#ModalForm .province_id,#ModalForm .regency_id,#ModalForm .village_id,#ModalForm .district_id').select2();
    $('#ModalForm').attr({'action':'{{ url('admin-cabang/update') }}'});
</script>