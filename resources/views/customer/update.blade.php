@csrf
<input type="hidden" name="data_pelanggan" value="{{ $data->cust_id }}">
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
                            <option @if($cabang->cab_id == $data->cab_id) selected @endif value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <label class="col-sm-2 col-form-label" for="kode_produk">Nomor Pelanggan</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_pelanggan_text" disabled value="{{ $data->kode }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="punya_npwp">Memiliki NPWP ?</label>
            <div class="col-sm-2">
                <select name="punya_npwp" id="punya_npwp" class="form-control" required onchange="$(this).val()==0 ? $('.flag-npwp').hide() : $('.flag-npwp').show()">
                    <option value="0" @if($data->npwp==0) selected @endif>Tidak</option>
                    <option value="1" @if($data->npwp==1) selected @endif>Ya</option>
                </select>
            </div>
            <label class="flag-npwp offset-sm-2 col-sm-2 col-form-label" for="nomor_npwp" @if($data->npwp==0) style="display:none" @endif>Nomor NPWP</label>
            <div class="flag-npwp col-sm-4" @if($data->npwp==0) style="display:none" @endif>
                <input type="text" id="nomor_npwp" name="nomor_npwp" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_pelanggan">Nama Lengkap Pelanggan / Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required value="{{ $data->fullname }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_ktp">Nomor KTP / Akta Pendirian</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_ktp" name="nomor_ktp" required value="{{ $data->no_ktp }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_perusahaan">Alamat Pelanggan / Perusahaan</label>
            <div class="col-sm-10">
                <input type="text" name="alamat_perusahaan" id="alamat_perusahaan" class="form-control" required value="{{ $data->address_01 }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_desa">Desa</label>
            <div class="col-sm-4">
                <select name="nama_desa" id="nama_desa" class="form-control village_id"></select>
            </div>
            <label class="col-sm-2 col-form-label">Kecamatan</label>
            <div class="col-sm-4">
                <select name="nama_kecamatan" id="nama_kecamatan" class="form-control district_id" onchange="getDesa(this,{{ $data->village_id }})" required></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_kabupaten">Kabupaten</label>
            <div class="col-sm-4">
                <select name="nama_kabupaten" id="nama_kabupaten" class="form-control regency_id" onchange="getKec(this,{{ $data->district_id }})" required></select>
            </div>
            <label class="col-sm-2 col-form-label" for="nama_provinsi">Provinsi</label>
            <div class="col-sm-4">
                <select name="nama_provinsi" id="nama_provinsi" class="form-control province_id" onchange="getKab(this,{{ $data->regency_id }})" required>
                    @if($provs)
                        @foreach($provs as $key => $prov)
                            <option @if($prov->id == $data->province_id) selected @endif value="{{ $prov->id }}">{{ ucwords(strtolower($prov->name)) }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kode_pos">Kode Pos</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="kode_pos" id="kode_pos" required value="{{ $data->postal_code }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_telp_pelanggan">No. Telp. Pelanggan</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nomor_telp_pelanggan" name="nomor_telp_pelanggan" value="{{ $data->phone }}">
            </div>
            <label class="col-sm-2 col-form-label" for="email_pelanggan">Email Pelanggan</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" id="email_pelanggan" name="email_pelanggan" value="{{ $data->email }}">
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-perusahaan" role="tabpanel" aria-labelledby="nav-perusahaan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_penanggunjawab">Nama Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_penanggunjawab" id="nama_penanggunjawab" value="{{ $data->penjab_name }}">
            </div>
            <label class="col-sm-2 col-form-label" for="jabatan_penanggungjawab">Jabatan Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jabatan_penanggungjawab" id="jabatan_penanggungjawab" value="{{ $data->penjab_jab }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="no_telp_penanggungjawab">No. Telp. Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_penanggungjawab" id="no_telp_penanggungjawab" value="{{ $data->penjab_phone }}">
            </div>
            <label class="col-sm-2 col-form-label" for="email_penanggungjawab">Email Penanggungjawab</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_penanggungjawab" id="email_penanggungjawab" value="{{ $data->penjab_email }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_teknisi">Nama Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_teknisi" id="nama_teknisi" value="{{ $data->tech_name }}">
            </div>
            <label class="col-sm-2 col-form-label" for="jabatan_teknisi">Jabatan Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="jabatan_teknisi" id="jabatan_teknisi" value="{{ $data->tech_jab }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="no_telp_teknisi">No. Telp. Teknisi</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_teknisi" id="no_telp_teknisi" value="{{ $data->tech_phone }}">
            </div>
            <label class="col-sm-2 col-form-label" for="email_teknisi">Email Teknisi</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_teknisi" id="email_teknisi" value="{{ $data->tech_email }}">
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-tagihan" role="tabpanel" aria-labelledby="nav-tagihan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_order">Nomor Order</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_order" id="nomor_order" value="{{ $data->order_num }}">
            </div>
            <label class="col-sm-2 col-form-label" for="nomor_purchase_order">Nomor Purchase Order</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_purchase_order" id="nomor_purchase_order" value="{{ $data->po_num }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nomor_quotation">Nomor Quotation</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nomor_quotation" id="nomor_quotation" value="{{ $data->quo_num }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_penanggungjawab_keuangan">Nama Penanggungjawab Keuangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama_penanggungjawab_keuangan" id="nama_penanggungjawab_keuangan" value="{{ $data->finance_name }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="alamat_penagihan">Alamat Penagihan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="alamat_penagihan" id="alamat_penagihan" required value="{{ $data->pas_address01 }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="desa_penagihan">Desa</label>
            <div class="col-sm-4">
                <select name="desa_penagihan" id="desa_penagihan" class="form-control" required></select>
            </div>
            <label class="col-sm-2 col-form-label" for="kecamatan_penagihan">Kecamatan</label>
            <div class="col-sm-4">
                <select name="kecamatan_penagihan" id="kecamatan_penagihan" class="form-control" required onchange="getDesaPenagihan({{ $data->pas_village_id }})"></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="kabupaten_penagihan">Kabupaten</label>
            <div class="col-sm-4">
                <select name="kabupaten_penagihan" id="kabupaten_penagihan" class="form-control" required onchange="getKecPenagihan({{ $data->pas_district_id }})"></select>
            </div>
            <label class="col-sm-2 col-form-label" for="provinsi_penagihan">Provinsi</label>
            <div class="col-sm-4">
                <select name="provinsi_penagihan" id="provinsi_penagihan" class="form-control" required onchange="getKabPenagihan({{ $data->pas_regency_id }})">
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
                <input type="text" class="form-control" name="kode_pos_penagihan" id="kode_pos_penagihan" required value="{{ $data->pas_postal }}">
            </div>
            <label class="col-sm-2 col-form-label" for="no_telp_penagihan">No. Telp</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="no_telp_penagihan" id="no_telp_penagihan" required value="{{ $data->pas_phone }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="email_penagihan">Email</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" name="email_penagihan" id="email_penagihan" required value="{{ $data->tagih_email }}">
            </div>
        </div>
    </div>
    <div style="padding-top:20px" class="tab-pane fade show" id="nav-layanan" role="tabpanel" aria-labelledby="nav-layanan-tab">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk</label>
            <div class="col-sm-10">
                <select name="nama_produk" id="nama_produk" class="form-control" required>
                    @if($products)
                        @foreach($products as $key => $product)
                            <option @if($product->pac_id == $data->pac_id) selected @endif value="{{ $product->pac_id }}">{{ $product->pac_name }} | {{ $product->price_format }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="ip-wrapper">
            <?php $ips = explode(',',$data->pas_ip); ?>
            @forelse($ips as $key => $ip)
                <div class="form-group row ip-{{ $key }} ip-list">
                    <label class="col-sm-2 col-form-label" for="alamat_ip">Alamat IP</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" value="{{ $ip }}" name="alamat_ip[]" class="form-control">
                            <div class="input-group-append">
                                <button onclick="$('.ip-{{ $key }}').remove()" class="btn btn-outline-danger" type="button"><i class="fa fa-trash"></i></button>
                                <button type="button" onclick="addIP()" class="btn btn-outline-success" title="Tambah IP lagi"><i class="fa fa-plus-circle"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
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
            @endforelse
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="promosi">Promosi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="promosi" id="promosi" value="{{ $data->pas_promo }}">
            </div>
        </div>
        <div class="row form-group">
            <label class="col-sm-2 col-form-label" for="jenis_pembayaran">Jenis Pembayaran</label>
            <div class="col-sm-4">
                <select name="jenis_pembayaran" id="jenis_pembayaran" class="form-control">
                    <option @if($data->paid_tipe == 'pre') selected @endif value="pre">Prepaid</option>
                    <option @if($data->paid_tipe == 'post') selected @endif value="post">Postpaid</option>
                </select>
            </div>
            <label class="col-sm-2 col-form-label" for="biaya_instalasi">Biaya Instalasi</label>
            <div class="col-sm-4">
                <input min="0" max="9999999999" value="{{ $data->pas_price }}" type="number" class="form-control" name="biaya_instalasi" id="biaya_instalasi">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="durasi_berlangganan">Durasi Berlangganan</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" value="{{ $data->duration }}" min="1" max="9999999999" name="durasi_berlangganan" id="durasi_berlangganan" class="form-control">
                    <div class="input-group-append">
                        <span class="input-group-text">Bulan</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="tanggal_pemasangan">Tanggal Pemasangan</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data->pas_date }}" name="tanggal_pemasangan" class="form-control" id="tanggal_pemasangan" required>
            </div>
            <label class="col-sm-2 col-form-label" for="tanggal_berlangganan">Tanggal Berlangganan</label>
            <div class="col-sm-4">
                <input type="text" value="{{ $data->from_date }}" name="tanggal_berlangganan" class="form-control" id="tanggal_berlangganan" required>
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
    $('#tanggal_berlangganan,#tanggal_pemasangan').datepicker({
        autoclose   : true,
        format      : 'yyyy-mm-dd'
    });
    getKab($('#ModalForm .province_id'));
    getKabPenagihan({{ $data->pas_regency_id }});
    $('#jenis_pembayaran,#nama_produk,#nama_cabang,.village_id,.regency_id,.province_id,.district_id,#provinsi_penagihan,#kabupaten_penagihan,#kecamatan_penagihan,#desa_penagihan').select2();
    $('#ModalForm').attr({'action':'{{ url('admin-customer/update') }}'});
</script>