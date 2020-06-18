<div id="content">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Cabang</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->cabang->cab_name }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">ID Pelanggan</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->kode }}</div></div>
        @if($data->npwp == 1)
            <label class="col-sm-2 col-form-label">Nomor NPWP</label>
            <div class="col-sm-4"><div class="form-control">{{ $data->npwp_nomor }}</div> </div>
        @endif
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nama Lengkap</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->fullname }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nomor KTP</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->no_ktp }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Alamat</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->address_01 }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Desa</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->desa->name }}</div> </div>
        <label class="col-sm-2 col-form-label">Kecamatan</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->kecamatan->name }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kabupaten</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->kabupaten->name }}</div> </div>
        <label class="col-sm-2 col-form-label">Provinsi</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->provinsi->name }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kode Pos</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->postal_code }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">No. Telp</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->phone }}</div> </div>
        <label class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->email }}</div> </div>
    </div>
</div>