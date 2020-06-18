<div id="content">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama Penanggungjawab</label>
        <div class="col-sm-9"><div class="form-control">{{ $data->penjab_name }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Jabatan Penanggungjawab</label>
        <div class="col-sm-6"><div class="form-control">{{ $data->penjab_jab }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">No. Telp Penanggungjawab</label>
        <div class="col-sm-3"><div class="form-control">{{ $data->penjab_phone }}</div></div>
        <label class="col-sm-3 col-form-label">Email Penanggungjawab</label>
        <div class="col-sm-3"><div class="form-control">{{ $data->penjab_email }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama Teknisi</label>
        <div class="col-sm-9"><div class="form-control">{{ $data->tech_name }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Jabatan Teknisi</label>
        <div class="col-sm-6"><div class="form-control">{{ $data->tech_jab }}</div></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">No. Telp Teknisi</label>
        <div class="col-sm-3"><div class="form-control">{{ $data->tech_phone }}</div></div>
        <label class="col-sm-3 col-form-label">Email Teknisi</label>
        <div class="col-sm-3"><div class="form-control">{{ $data->tech_email }}</div></div>
    </div>
</div>