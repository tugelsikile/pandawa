@csrf
<input type="hidden" name="data_jenis_layanan" value="{{ $data->first()->id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Jenis Layanan</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_jenis_layanan" value="{{ $data->first()->name }}">
    </div>
</div>