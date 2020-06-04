@csrf
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Cabang</label>
    <div class="col-sm-10">
        <select name="nama_cabang" id="nama_cabang" class="form-control" required onchange="kodeProduk()">
            <option value="">=== Cabang ===</option>
            @if($cabangs)
                @foreach($cabangs as $key => $cabang)
                    <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="kode_produk">Kode Produk</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="kode_produk" name="kode_produk" required>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="nama_produk" id="nama_produk" required>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="keterangan_produk">Keterangan Produk</label>
    <div class="col-sm-10">
        <textarea name="keterangan_produk" id="keterangan_produk" class="form-control"></textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="kapasitas">Kapasitas</label>
    <div class="col-sm-2">
        <input type="number" min="0" max="999999" value="10" name="kapasitas" id="kapasitas" class="form-control" required>
    </div>
    <div class="col-sm-2">
        <select name="besaran_kapasitas" class="form-control" style="width: 100%" id="besaran_kapasitas" required>
            <option value="Kbps">Kbps</option>
            <option value="Mbps">Mbps</option>
            <option value="Gbps">Gbps</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="harga_produk">Harga Produk</label>
    <div class="col-sm-4">
        <input onchange="previewHarga()" onkeyup="previewHarga()" type="number" min="0" max="9999999999" value="100000" name="harga_produk" id="harga_produk" class="form-control" required>
    </div>
    <label class="col-sm-4 col-form-label" for="pajak_produk">Pajak %</label>
    <div class="col-sm-2">
        <input onchange="previewHarga()" onkeyup="previewHarga()" type="number" min="0" max="100" value="0" name="pajak_produk" id="pajak_produk" class="form-control" required>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Preview Harga</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="preview_harga" disabled>
    </div>
</div>
<script>
    if ($('.cab-id').val().length > 0){
        $('#nama_cabang').val($('.cab-id').val());
    }
    $('#nama_cabang').select2();
    kodeProduk();
    previewHarga();
    $('#nama_cabang,#besaran_kapasitas').select2();
    $('#ModalForm').attr({'action':'{{ url('admin-produk/create') }}'});
</script>