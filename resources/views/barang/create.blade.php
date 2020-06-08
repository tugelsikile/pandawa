@csrf

<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_barang">Nama Barang</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_barang" id="nama_barang">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="mac_address">Mac Address / Serial Number</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="mac_address" id="mac_address">
    </div>
    <label class="col-sm-2 col-form-label" for="tanggal_pembelian">Tanggal Pembelian</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="tanggal_pembelian" id="tanggal_pembelian">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="harga_pembelian">Harga Pembelian</label>
    <div class="col-sm-4">
        <input type="number" min="0" max="9999999999" class="form-control" name="harga_pembelian" id="harga_pembelian">
    </div>
    <label class="col-sm-2 col-form-label" for="harga_penjualan">Harga Penjualan</label>
    <div class="col-sm-4">
        <input type="number" min="0" max="9999999999" class="form-control" name="harga_penjualan" id="harga_penjualan">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="kondisi_barang">Kondisi Barang</label>
    <div class="col-sm-4">
        <select name="kondisi_barang" class="form-control" id="kondisi_barang">
            <option value="Baru">Baru</option>
            <option value="Baik">Baik</option>
            <option value="Rusak Ringan">Rusak Ringan</option>
            <option value="Rusak Berat">Rusak Berat</option>
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