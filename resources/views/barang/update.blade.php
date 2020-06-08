@csrf
<input type="hidden" name="data_barang" value="{{ $data->br_id }}">
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_barang">Nama Barang</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_barang" id="nama_barang" value="{{ $data->nama_barang }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="mac_address">Mac Address / Serial Number</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="mac_address" id="mac_address" value="{{ $data->mac_address }}">
    </div>
    <label class="col-sm-2 col-form-label" for="tanggal_pembelian">Tanggal Pembelian</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="tanggal_pembelian" id="tanggal_pembelian" value="{{ date('Y-m-d',strtotime($data->date_buy)) }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="harga_pembelian">Harga Pembelian</label>
    <div class="col-sm-4">
        <input type="number" value="{{ $data->price_buy }}" min="0" max="9999999999" class="form-control" name="harga_pembelian" id="harga_pembelian">
    </div>
    <label class="col-sm-2 col-form-label" for="harga_penjualan">Harga Penjualan</label>
    <div class="col-sm-4">
        <input type="number" value="{{ $data->price_sell }}" min="0" max="9999999999" class="form-control" name="harga_penjualan" id="harga_penjualan">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="kondisi_barang">Kondisi Barang</label>
    <div class="col-sm-4">
        <select name="kondisi_barang" class="form-control" id="kondisi_barang">
            <option @if($data->kondisi == 'Baru') selected @endif value="Baru">Baru</option>
            <option @if($data->kondisi == 'Baik') selected @endif value="Baik">Baik</option>
            <option @if($data->kondisi == 'Rusak Ringan') selected @endif value="Rusak Ringan">Rusak Ringan</option>
            <option @if($data->kondisi == 'Rusak Berat') selected @endif value="Rusak Berat">Rusak Berat</option>
        </select>
    </div>
</div>

<script>
    $('#tanggal_pembelian').datepicker({
        autoclose : true,
        format : 'yyyy-mm-dd'
    })
    $('#kondisi_barang').select2();
</script>