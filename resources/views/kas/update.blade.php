@csrf
<input type="hidden" name="data_kas" value="{{ $data->id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="jenis_kas">Jenis Kas</label>
    <div class="col-sm-4">
        <select name="jenis_kas" id="jenis_kas" class="form-control">
            <option value="">=== Pilih Jenis Kas ===</option>
            <option @if($data->kategori === 'pengeluaran') selected @endif value="pengeluaran">Pengeluaran</option>
            <option @if($data->kategori === 'pemasukan') selected @endif value="pemasukan">Pemasukan</option>
            <option @if($data->kategori === 'piutang') selected @endif value="piutang">Piutang</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="tanggal_kas">Tanggal Kas</label>
    <div class="col-sm-2">
        <input value="{{ $data->kas_date }}" type="text" name="tanggal_kas" id="tanggal_kas" class="form-control">
    </div>
    <label class="col-sm-2 offset-2 col-form-label" for="nomor_bukti">Nomor Bukti</label>
    <div class="col-sm-4">
        <input value="{{ $data->nomor_bukti }}" type="text" id="nomor_bukti" name="nomor_bukti" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="uraian_kas">Uraian Kas</label>
    <div class="col-sm-10">
        <textarea name="uraian_kas" id="uraian_kas" class="form-control" rows="3">{{ $data->informasi }}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="jumlah_kas">Jumlah Kas</label>
    <div class="col-sm-4">
        <input type="number" min="0" max="999999999999999" value="{{ $data->ammount }}" name="jumlah_kas" id="jumlah_kas" class="form-control">
    </div>
</div>

<script>
    $('#tanggal_kas').datepicker({
        autoclose : true,
        format : 'yyyy-mm-dd'
    })
</script>