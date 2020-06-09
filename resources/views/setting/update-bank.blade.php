@csrf
<input type="hidden" name="data_bank" value="{{ $data->bank_id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_bank">Nama Bank</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_bank" id="nama_bank" value="{{ $data->bank_name }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="cabang_bank">Cabang Bank</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="cabang_bank" id="cabang_bank" value="{{ $data->bank_cabang }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_pemilik_rekening">Nama Pemilik Rekening</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_pemilik_rekening" id="nama_pemilik_rekening" value="{{ $data->bank_fullname }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nomor_rekening">Nomor Rekening</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" value="{{ $data->bank_rekening }}">
    </div>
</div>