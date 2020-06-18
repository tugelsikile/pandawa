<div id="content">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nama Produk</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->produk->pac_name }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Harga Produk</label>
        <div class="col-sm-4">
            <div class="form-control">
                Rp. {{ format_rp($data->produk->price_with_tax) }}
            </div>
            @if($data->produk->tax_percent>0)
                <small class="text-muted">* Harga termasuk pajak {{ $data->produk->tax_percent }}%</small>
            @endif
        </div>
        <label class="col-sm-2 col-form-label">Kapasitas</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->produk->cap }} {{ $data->produk->cap_byte }}</div> </div>
    </div>
    <?php $ips = explode(',',$data->ip) ?>
    @if(count($ips)>0)
        @foreach($ips as $ip)
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Alamat IP</label>
            <div class="col-sm-4"><div class="form-control">{{ $ip }}</div></div>
        </div>
        @endforeach
    @endif
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Promosi</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->pas_promo }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Jenis Pembayaran</label>
        <div class="col-sm-4"><div class="form-control">{{ ucwords($data->paid_tipe) }}paid</div> </div>
        <label class="col-sm-2 col-form-label">Biaya Instalasi</label>
        <div class="col-sm-4"><div class="form-control">Rp. {{ format_rp($data->pas_price) }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Durasi Berlangganan</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->duration }} bulan</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Tgl Pemasangan</label>
        <div class="col-sm-4"><div class="form-control">{{ tglIndo($data->pas_date) }}</div> </div>
        <label class="col-sm-2 col-form-label">Tgl Mulai Berlangganan</label>
        <div class="col-sm-4"><div class="form-control">{{ tglIndo($data->from_date) }}</div> </div>
    </div>
    @if($data->is_active == 0)
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pelanggan Non Aktif</label>
            <div class="col-sm-4"><div class="form-control">Ya</div></div>
            @if(strlen($data->nonactive_date)>0)
                <label class="col-sm-2 col-form-label">Tanggal Non Aktif</label>
                <div class="col-sm-4"><div class="form-control">{{ tglIndo($data->nonactive_date) }}</div> </div>
            @endif
        </div>
    @endif
</div>