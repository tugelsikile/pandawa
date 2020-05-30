@csrf
<div class="form-group">
    <label for="cab_name" class="col-sm-2 control-label">Nama Cabang</label>
    <div class="col-sm-10">
        <input name="cab_name" id="cab_name" type="text" class="cab_name form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Desa / Kelurahan</label>
    <div class="col-sm-4">
        <select name="village_id" class="form-control village_id" style="width:100%">
            <option value="">=== Desa ===</option>
        </select>
    </div>
    <label class="col-sm-2 control-label">Kecamatan</label>
    <div class="col-sm-4">
        <select name="district_id" class="form-control district_id" style="width:100%;" onchange="getDesa(this)">
            <option value="">=== Kecamatan ===</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Kab. / Kota</label>
    <div class="col-sm-4">
        <select name="regency_id" class="form-control regency_id" style="width:100%;" onchange="getKec(this)">
            <option value="">=== Kabupaten ===</option>
        </select>
    </div>
    <label class="col-sm-2 control-label">Provinsi</label>
    <div class="col-sm-4">
        <select name="province_id" class="form-control province_id" style="width:100%;" onchange="getKab(this)">
            @if($prov)
                @foreach($prov as $key => $val)
                    <option value="{{ $val->id }}">{{ ucwords(strtolower($val->name)) }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Kode Pos</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="postal">
    </div>
</div>
<script>
    getKab($('#ModalForm .province_id'));
    $('#ModalForm .province_id,#ModalForm .regency_id,#ModalForm .village_id,#ModalForm .district_id').select2();
    $('#ModalForm').attr({'action':'{{ url('admin-cabang/create') }}'});
</script>