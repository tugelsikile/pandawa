@csrf
<input type="hidden" name="data_pengeluaran" value="{{ $data->id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="jenis_pengeluaran">Jenis Pengeluaran</label>
    <div class="col-sm-10">
        <textarea name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control" rows="3">{{ $data->deskripsi }}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="tanggal_mulai_pengeluaran">Tanggal Mulai</label>
    <div class="col-sm-4">
        <input type="text" value="{{ $data->start_date }}" name="tanggal_mulai_pengeluaran" id="tanggal_mulai_pengeluaran" class="form-control">
    </div>
    <label class="col-sm-2 col-form-label" for="tanggal_akhir_pengeluaran">Tanggal Akhir</label>
    <div class="col-sm-4">
        <input type="text" value="{{ $data->end_date }}" name="tanggal_akhir_pengeluaran" id="tanggal_akhir_pengeluaran" class="form-control" placeholder="Kosongkan jika ingin selamanya">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="jumlah_pengeluaran">Jumlah Pengeluaran</label>
    <div class="col-sm-4">
        <input type="number" value="{{ $data->ammount }}" min="0" max="999999999999" name="jumlah_pengeluaran" id="jumlah_pengeluaran" class="form-control">
    </div>
</div>
<script>
    $('#tanggal_mulai_pengeluaran,#tanggal_akhir_pengeluaran').datepicker({
        format  : 'yyyy-mm-dd',
        autoclose : true
    })
</script>