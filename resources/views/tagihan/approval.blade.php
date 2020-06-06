@csrf
<input type="hidden" name="data_tagihan" value="{{ $data->inv_id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Cabang</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" value="{{ $data->cabang->cab_name }}" disabled>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Customer</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" disabled value="{{ $data->customer->fullname }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Paket / Layanan</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" disabled value="{{ $data->paket->pac_name }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Harga Paket / Layanan</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" value="Rp. {{ format_rp($data->paket->price_with_tax) }}" disabled>
    </div>
    <label class="col-sm-2 col-form-label" for="tanggal_approval">Tanggal Approval</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="tanggal_approval" id="tanggal_approval" value="{{ date('Y-m-d') }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Approved</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="keterangan_approval">Keterangan</label>
    <div class="col-sm-10">
        <textarea name="keterangan_approval" class="form-control" id="keterangan_approval"></textarea>
    </div>
</div>

<script>
    $('#tanggal_approval').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    })
</script>