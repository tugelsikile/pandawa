@csrf
@foreach($data as $invoice)
    <input type="hidden" name="inv_id[]" value="{{$invoice->inv_id}}">
@endforeach
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Jumlah Tagihan</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" disabled value="{{count($data)}}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Total Tagihan</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" value="Rp. {{ format_rp($data->sum('price_with_tax')) }}" disabled>
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