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
    <label class="col-sm-2 col-form-label">Tanggal Approval</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" disabled value="{{ date('Y-m-d',strtotime($data->paid_date)) }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Nama Approved</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" value="{{ $data->approved->name }}" disabled>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Keterangan Approval</label>
    <div class="col-sm-10">
        <textarea class="form-control" disabled>{{ $data->notes }}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="tanggal_pembatalan">Tanggal Pembatalan</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="tanggal_pembatalan" id="tanggal_pembatalan" value="{{ date('Y-m-d') }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="keterangan_pembatalan">Keterangan Pembatalan</label>
    <div class="col-sm-10">
        <textarea name="keterangan_pembatalan" id="keterangan_pembatalan" class="form-control"></textarea>
    </div>
</div>
<script>
    $('#tanggal_pembatalan').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    })
</script>