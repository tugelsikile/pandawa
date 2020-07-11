@csrf
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-pelanggan-tab" data-toggle="tab" href="#nav-pelanggan" role="tab" aria-controls="nav-pelanggan" aria-selected="true">DATA PELANGGAN</a>
        <a class="nav-item nav-link" id="nav-perusahaan-tab" data-toggle="tab" href="#nav-perusahaan" role="tab" aria-controls="nav-perusahaan" aria-selected="false">INFO PERUSAHAAN</a>
        <a class="nav-item nav-link" id="nav-tagihan-tab" data-toggle="tab" href="#nav-tagihan" role="tab" aria-controls="nav-tagihan" aria-selected="false">INFO TAGIHAN</a>
        <a class="nav-item nav-link" id="nav-layanan-tab" data-toggle="tab" href="#nav-layanan" role="tab" aria-controls="nav-layanan" aria-selected="false">LAYANAN</a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div style="padding-top:20px" class="tab-pane fade show active" id="nav-pelanggan" role="tabpanel" aria-labelledby="nav-pelanggan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Cabang</label>
            <div class="col-sm-4">
                <select name="nama_cabang" id="nama_cabang" class="form-control" required onchange="kodeCustomer();getProdukCabang(this)">
                    <option value="">=== Cabang ===</option>
                    @if($cabangs)
                        @foreach($cabangs as $key => $cabang)
                            <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <label class="col-sm-2 col-form-label" for="kode_produk">Nomor Pelanggan</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_pelanggan_text" disabled>
                <input type="hidden" name="nomor_pelanggan" id="nomor_pelanggan">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="punya_npwp">Memiliki NPWP ?</label>
            <div class="col-sm-2">
                <select name="punya_npwp" id="punya_npwp" class="form-control" required onchange="$(this).val()==0 ? $('.flag-npwp').hide() : $('.flag-npwp').show()">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
            </div>
            <label class="flag-npwp offset-sm-2 col-sm-2 col-form-label" for="nomor_npwp">Nomor NPWP</label>
            <div class="flag-npwp col-sm-4">
                <input type="text" id="nomor_npwp" name="nomor_npwp" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_pelanggan">Nama Lengkap Pelanggan / Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_ktp">Nomor KTP / Akta Pendirian</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_ktp" name="nomor_ktp" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_perusahaan">Alamat Pelanggan / Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" name="alamat_perusahaan" id="alamat_perusahaan" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_desa">Desa</label>
            <div class="col-sm-4">
                <select name="nama_desa" id="nama_desa" class="form-control village_id"></select>
            </div>
            <label class="col-sm-2 col-form-label">Kecamatan</label>
            <div class="col-sm-4">
                <select name="nama_kecamatan" id="nama_kecamatan" class="form-control district_id" onchange="getDesa(this)" required></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_kabupaten">Kabupaten</label>
            <div class="col-sm-4">
                <select name="nama_kabupaten" id="nama_kabupaten" class="form-control regency_id" onchange="getKec(this)" required></select>
            </div>
            <label class="col-sm-2 col-form-label" for="nama_provinsi">Provinsi</label>
            <div class="col-sm-4">
                <select name="nama_provinsi" id="nama_provinsi" class="form-control province_id" onchange="getKab(this)" required>
                    @if($provs)
                        @foreach($provs as $key => $prov)
                            <option @if($prov->id == 32) selected @endif value="{{ $prov->id }}">{{ ucwords(strtolower($prov->name)) }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kode_pos">Kode Pos</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="kode_pos" id="kode_pos" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_telp_pelanggan">No. Telp. Pelanggan</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_telp_pelanggan" name="nomor_telp_pelanggan">
            </div>
            <label class="col-sm-2 col-form-label" for="email_pelanggan">Email Pelanggan</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" id="email_pelanggan" name="email_pelanggan">
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-perusahaan" role="tabpanel" aria-labelledby="nav-perusahaan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_penanggunjawab">Nama Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_penanggunjawab" id="nama_penanggunjawab">
            </div>
            <label class="col-sm-2 col-form-label" for="jabatan_penanggungjawab">Jabatan Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jabatan_penanggungjawab" id="jabatan_penanggungjawab">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="no_telp_penanggungjawab">No. Telp. Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_penanggungjawab" id="no_telp_penanggungjawab">
            </div>
            <label class="col-sm-2 col-form-label" for="email_penanggungjawab">Email Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_penanggungjawab" id="email_penanggungjawab">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_teknisi">Nama Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_teknisi" id="nama_teknisi">
            </div>
            <label class="col-sm-2 col-form-label" for="jabatan_teknisi">Jabatan Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jabatan_teknisi" id="jabatan_teknisi">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="no_telp_teknisi">No. Telp. Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_teknisi" id="no_telp_teknisi">
            </div>
            <label class="col-sm-2 col-form-label" for="email_teknisi">Email Teknisi</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_teknisi" id="email_teknisi">
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-tagihan" role="tabpanel" aria-labelledby="nav-tagihan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_order">Nomor Order</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_order" id="nomor_order">
            </div>
            <label class="col-sm-2 col-form-label" for="nomor_purchase_order">Nomor Purchase Order</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_purchase_order" id="nomor_purchase_order">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_quotation">Nomor Quotation</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_quotation" id="nomor_quotation">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_penanggungjawab_keuangan">Nama Penanggungjawab Keuangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama_penanggungjawab_keuangan" id="nama_penanggungjawab_keuangan">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_penagihan">Alamat Penagihan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="alamat_penagihan" id="alamat_penagihan" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="desa_penagihan">Desa</label>
            <div class="col-sm-4">
                <select name="desa_penagihan" id="desa_penagihan" class="form-control" required></select>
            </div>
            <label class="col-sm-2 col-form-label" for="kecamatan_penagihan">Kecamatan</label>
            <div class="col-sm-4">
                <select name="kecamatan_penagihan" id="kecamatan_penagihan" class="form-control" required onchange="getDesaPenagihan()"></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kabupaten_penagihan">Kabupaten</label>
            <div class="col-sm-4">
                <select name="kabupaten_penagihan" id="kabupaten_penagihan" class="form-control" required onchange="getKecPenagihan()"></select>
            </div>
            <label class="col-sm-2 col-form-label" for="provinsi_penagihan">Provinsi</label>
            <div class="col-sm-4">
                <select name="provinsi_penagihan" id="provinsi_penagihan" class="form-control" required onchange="getKabPenagihan()">
                    @if($provs)
                        @foreach($provs as $key => $prov)
                            <option @if($prov->id == 32) selected @endif value="{{ $prov->id }}">{{ ucwords(strtolower($prov->name)) }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kode_pos_penagihan">Kode Pos</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="kode_pos_penagihan" id="kode_pos_penagihan" required>
            </div>
            <label class="col-sm-2 col-form-label" for="no_telp_penagihan">No. Telp</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_penagihan" id="no_telp_penagihan" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="email_penagihan">Email</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_penagihan" id="email_penagihan" required>
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-layanan" role="tabpanel" aria-labelledby="nav-layanan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_jenis_layanan">Jenis Layanan</label>
            <div class="col-sm-10">
                <select class="form-control" id="nama_jenis_layanan" name="nama_jenis_layanan">
                    <option value="">=== Pilih Jenis Layanan ===</option>
                    @forelse($jenis as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk</label>
            <div class="col-sm-10">
                <select name="nama_produk" id="nama_produk" class="form-control" required></select>
            </div>
        </div>
        <div class="ip-wrapper">
            <div class="form-group row ip-0 ip-list">
                <label class="col-sm-2 col-form-label" for="alamat_ip">Alamat IP</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" name="alamat_ip[]" class="form-control">
                        <div class="input-group-append">
                            <button onclick="$('.ip-0').remove()" class="btn btn-outline-danger" type="button"><i class="fa fa-trash"></i></button>
                            <button type="button" onclick="addIP()" class="btn btn-outline-success" title="Tambah IP lagi"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="promosi">Promosi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="promosi" id="promosi">
            </div>
        </div>
        <div class="row form-group">
            <label class="col-sm-2 col-form-label" for="jenis_pembayaran">Jenis Pembayaran</label>
            <div class="col-sm-4">
                <select name="jenis_pembayaran" id="jenis_pembayaran" class="form-control">
                    <option value="pre">Prepaid</option>
                    <option value="post">Postpaid</option>
                </select>
            </div>
            <label class="col-sm-2 col-form-label" for="biaya_instalasi">Biaya Instalasi</label>
            <div class="col-sm-4">
                <input min="0" max="9999999999" value="0" type="number" class="form-control" name="biaya_instalasi" id="biaya_instalasi">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="durasi_berlangganan">Durasi Berlangganan</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" value="1" min="1" max="9999999999" name="durasi_berlangganan" id="durasi_berlangganan" class="form-control">
                    <div class="input-group-append">
                        <span class="input-group-text">Bulan</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="tanggal_pemasangan">Tanggal Pemasangan</label>
            <div class="col-sm-4">
                <input type="text" name="tanggal_pemasangan" class="form-control" id="tanggal_pemasangan" required>
            </div>
            <label class="col-sm-2 col-form-label" for="tanggal_berlangganan">Tanggal Berlangganan</label>
            <div class="col-sm-4">
                <input type="text" name="tanggal_berlangganan" class="form-control" id="tanggal_berlangganan" required>
            </div>
        </div>
    </div>
</div>

<script>
    function addIP() {
        var datalength = $('.ip-list').length;
        var html = '' +
            '<div class="form-group row ip-'+datalength+' ip-list">\n' +
                '<label class="col-sm-2 col-form-label" for="alamat_ip">Alamat IP</label>\n' +
                '<div class="col-sm-4">\n' +
                    '<div class="input-group">\n' +
                        '<input type="text" name="alamat_ip[]" class="form-control">\n' +
                        '<div class="input-group-append">\n' +
                            '<button onclick="$(\'.ip-'+datalength+'\').remove()" class="btn btn-outline-danger" type="button"><i class="fa fa-trash"></i></button>\n' +
                            '<button type="button" onclick="addIP()" class="btn btn-outline-success" title="Tambah IP lagi"><i class="fa fa-plus-circle"></i></button>\n' +
                        '</div>\n' +
                    '</div>\n' +
                '</div>\n' +
            '</div>';
        $('.ip-wrapper').append(html);
    }
    if ($('.cab-id').val().length > 0){
        $('#nama_cabang').val($('.cab-id').val());
    }
    $('#tanggal_berlangganan,#tanggal_pemasangan').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });
    getKab($('#ModalForm .province_id'));
    getKabPenagihan();
    $('.flag-npwp').hide();
    $('#jenis_pembayaran,#nama_produk,#nama_cabang,.village_id,.regency_id,.province_id,.district_id,#provinsi_penagihan,#kabupaten_penagihan,#kecamatan_penagihan,#desa_penagihan').select2();
    $('.province_id').change(function () {
        $('#provinsi_penagihan').val($('.province_id').val()).select2();
        getKabPenagihan($('.regency_id').val());
    });
    $('.regency_id').change(function () {
        $('#kabupaten_penagihan').val($('.regency_id').val()).select2();
        getKecPenagihan($('.district_id').val());
    });
    $('.district_id').change(function () {
        $('#kecamatan_penagihan').val($('.district_id').val()).select2();
        getDesaPenagihan($('.village_id').val());
    });
    $('#alamat_perusahaan').change(function () {
        $('#alamat_penagihan').val($('#alamat_perusahaan').val());
    });
    $('#kode_pos').change(function () {
        $('#kode_pos_penagihan').val($('#kode_pos').val())
    });
    $('#nomor_telp_pelanggan').change(function(){
        $('#no_telp_penagihan').val($('#nomor_telp_pelanggan').val());
    });
    $('#email_pelanggan').change(function () {
        $('#email_penagihan').val($('#email_pelanggan').val());
    });
    $('#ModalForm').attr({'action':'{{ url('admin-customer/create') }}'});
</script>