<div id="content">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nomor Order</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->order_num }}</div></div>
        <label class="col-sm-2 col-form-label">Nomor Purchase Order</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->po_num }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nomor Quotation</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->quo_num }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nama Penanggungjawab Keuangan</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->finance_name }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Alamat Penagihan</label>
        <div class="col-sm-10"><div class="form-control">{{ $data->pas_address01 }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Desa</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pasang_desa->name }}</div> </div>
        <label class="col-sm-2 col-form-label">Kecamatan</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pasang_kecamatan->name }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kabupaten</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pasang_kabupaten->name }}</div> </div>
        <label class="col-sm-2 col-form-label">Provinsi</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pasang_provinsi->name }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Kode Pos</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pas_postal }}</div> </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">No. Telp</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->pas_phone }}</div> </div>
        <label class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-4"><div class="form-control">{{ $data->tagih_email }}</div> </div>
    </div>
</div>