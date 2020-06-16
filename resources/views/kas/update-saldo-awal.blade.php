@csrf
<input type="hidden" name="data_kas" value="{{ $data->id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Bulan Tahun</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" disabled value="{{ bulanIndo($data->bulan) }} {{ $data->tahun }}">
    </div>
    <label class="col-sm-2 col-form-label" for="saldo_awal">Saldo Awal</label>
    <div class="col-sm-4">
        <input type="number" min="-999999999999999" max="999999999999999" name="saldo_awal" id="saldo_awal" class="form-control" value="{{ $data->ammount }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label" for="kunci_saldo">Kunci jumlah saldo awal saat ini</label>
    <div class="col-sm-8">
        <input type="checkbox" @if($data->locked === 1) checked @endif name="kunci_saldo" id="kunci_saldo" value="1">
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <small class="text-muted">* Data mungkin berubah jika suatu waktu saldo akhir bulan sebelumnya berubah</small><br>
        <small class="text-muted">* Data saldo awal yang dikunci tidak akan berubah walaupun saldo akhir bulan sebelumnya berubah</small>
    </div>
</div>

<script>
    $('#kunci_saldo').bootstrapToggle({
        on      : 'Ya',
        off     : 'Tidak',
        size    : 'small',
        onstyle : 'danger',
        offstyle: 'success',
        width   : '100px'
    });
</script>