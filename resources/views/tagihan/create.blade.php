@csrf

<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_cabang">Nama Cabang</label>
    <div class="col-sm-10">
        <select name="nama_cabang" id="nama_cabang" class="form-control" onchange="listCustomer()">
            @if(count($cabangs)>0)
                @foreach($cabangs as $key => $cabang)
                    <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_pelanggan">Nama Pelanggan</label>
    <div class="col-sm-10">
        <select name="nama_pelanggan" id="nama_pelanggan" class="form-control" onchange="listProduk()"></select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk / Layanan</label>
    <div class="col-sm-10">
        <select name="nama_produk" id="nama_produk" class="form-control"></select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="bulan_tagihan">Bulan Tagihan</label>
    <div class="col-sm-4">
        <select name="bulan_tagihan" id="bulan_tagihan" class="form-control">
            @foreach(ArrayBulan() as $key => $bulan)
                <option @if($bulan['value']==date('m')) selected @endif value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>
            @endforeach
        </select>
    </div>
    <label class="col-sm-2 col-form-label" for="tahun_tagihan">Tahun Tagihan</label>
    <div class="col-sm-4">
        <select name="tahun_tagihan" id="tahun_tagihan" class="form-control">
            @for($tahun = MinTahun(); $tahun <= date('Y'); $tahun++)
                <option @if($tahun==date('Y')) selected @endif value="{{ $tahun }}">{{ $tahun }}</option>
            @endfor
        </select>
    </div>
</div>


<script>
    listCustomer();
    $('#nama_cabang,#nama_pelanggan,#nama_produk,#bulan_tagihan,#tahun_tagihan').select2();
</script>