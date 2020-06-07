<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cetak Laporan</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/cetak.min.css') }}" rel="stylesheet">

    <style>
        *{
            font-family: Nunito, sans-serif !important;
            font-size: 8pt !important;
        }
        .judul{
            text-align: center; font-weight: bold; font-size: 12pt !important; height:60px;
        }
        .kop td { font-size:10pt !important;}
        .kop td strong { font-size:16pt !important;}
        .it-grid td,.it-grid th,.it-grid td strong {font-size:8pt !important;}
        .float-left{ float: left; font-size:10pt;}
        .float-right{ float: right; font-size:10pt;}
        .page_number{
            position: absolute;bottom: 50px; right:50px; font-size:12pt;
        }
    </style>

</head>
<body>
<?php
$nomor = 1;
$total_tagihan = $dibayar = $belum_dibayar = $npwp = $non_npwp = $total_pajak = 0;
?>
@foreach($data as $key => $page)
    @foreach($page as $invoice)
        <?php
        $total_tagihan = $total_tagihan + $invoice->price_with_tax;
        if ($invoice->is_paid == 1) {
            $dibayar = $dibayar + $invoice->price_with_tax;
        } else {
            $belum_dibayar = $belum_dibayar + $invoice->price_with_tax;
        }
        if ($invoice->npwp == 1){
            $npwp = $npwp + $invoice->price_with_tax;
        } else {
            $non_npwp = $non_npwp + $invoice->price_with_tax;
        }
        $total_pajak = $total_pajak + ( $invoice->price_with_tax - $invoice->price );
        ?>
    @endforeach
@endforeach

<div class="page">
    <div style="margin:10px">
        <table width="100%" class="kop">
            <tr>
                <td width="100px" align="center" valign="middle"><img src="{{ asset('images/logo.png') }}" width="100%"></td>
                <td valign="middle" align="center">
                    <strong>{{ $companyInfo->company_name01 }}</strong><br>
                    {!! $companyInfo->address_01.' '.$companyInfo->address_02.'
                            '.ucwords(strtolower($companyInfo->kec_name)).'
                            '.ucwords(strtolower($companyInfo->kab_name)).'
                            '.ucwords(strtolower($companyInfo->prov_name)).'
                            Indonesia '.$companyInfo->postal_code.'<br>
                            <i class="fa fa-phone"></i> '.$companyInfo->phone.'
                            <i class="fa fa-envelope"></i> '.$companyInfo->email.'
                            <i class="fa fa-globe"></i> '.url('/') !!}
                </td>
            </tr>
        </table>
        <hr style="margin-bottom:10px">
        <div class="judul">{!! strtoupper($judul_laporan) !!}</div>
        <table width="100%" class="it-grid">
            <thead>
            <tr>
                <th width="40px"></th>
                <th width=""></th>
                <th width="100px"></th>
                <th width="150px"></th>
                <th width="100px"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="" colspan="3" align="right">Total Tagihan</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($total_tagihan) }}</span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Sudah Dibayar</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($dibayar) }}</span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Belum Dibayar</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($belum_dibayar) }}</span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Tagihan Pelanggan Dengan NPWP</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($npwp) }}</span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Tagihan Pelanggan Tanpa NPWP</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($non_npwp) }}</span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Pajak</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right">{{ format_rp($total_pajak) }}</span> </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

{{--<div class="page">
    <div style="margin:10px">
        <table width="100%" class="kop">
            <tr>
                <td width="100px" align="center" valign="middle"><img src="{{ asset('images/logo.png') }}" width="100%"></td>
                <td valign="middle" align="center">
                    <strong>{{ $companyInfo->company_name01 }}</strong><br>
                    {{ $companyInfo->address_01.' '.$companyInfo->address_02.'
                            '.ucwords(strtolower($companyInfo->kec_name)).'
                            '.ucwords(strtolower($companyInfo->kab_name)).'
                            '.ucwords(strtolower($companyInfo->prov_name)).'
                            Indonesia '.$companyInfo->postal_code.'<br>
                            <i class="fas fa-phone-alt"></i> '.$companyInfo->phone.'
                            <i class="fas fa-envelope"></i> '.$companyInfo->email.'
                            <i class="fas fa-globe"></i> '.url('/') }}
                </td>
            </tr>
        </table>
        <hr style="margin-bottom:10px">
        <div class="judul">{{ $judul_laporan }}</div>
        <table class="it-grid" width="100%">
            <thead>
            <tr>
                <th width="40px">No.</th>
                <th width="">Nama Pelanggan / Invoice Number</th>
                <th width="100px">Periode</th>
                <th width="100px">Tagihan <br><small>(sebelum pajak)</small></th>
                <th width="100px">Pajak</th>
                <th width="100px">Sub Total</th>
                <th width="100px">Keterangan</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($valPage as $valRow){
                $harga_normal   = $valRow->price;
                $harga_pajak    = $valRow->price_tax;
                $harga_with_tax = $valRow->price_with_tax;
                $ket    = '-';
                if ($valRow->is_paid == 1){
                    $ket = 'LUNAS';
                    $price_paid = $price_paid + $harga_with_tax;
                } else {
                    $price_unpaid = $price_unpaid + $harga_with_tax;
                }
                if ($valRow->npwp == 1){
                    $price_npwp = $price_npwp + $harga_with_tax;
                } else {
                    $price_nonnpwp = $price_nonnpwp + $harga_with_tax;
                }
                $price_all  = $price_all + $harga_with_tax;
                $allTax     = $allTax + $harga_pajak;
                echo '<tr>
                            <td align="center">'.$nomor.'</td>
                            <td>
                                <strong>'.$valRow->fullname.'</strong><br>
                                <small><em>'.$valRow->inv_number.'</em></small>
                            </td>
                            <td align="center">'.date('M Y',strtotime($valRow->inv_date)).'</td>
                            <td align="right"><span class="float-left">Rp.</span> <span class="float-right">'.number_format($harga_normal,0,'','.').'</span> </td>
                            <td align="right"><span class="float-left">Rp.</span> <span class="float-right">'.number_format($harga_pajak,0,'','.').'</span> </td>
                            <td align="right"><span class="float-left">Rp.</span> <span class="float-right">'.number_format($harga_with_tax,0,'','.').'</span> </td>
                            <td align="center">'.$ket.'</td>
                          </tr>';
                $nomor++;
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="page_number">Page <?=$num_page . ' of ' . count($data);?></div>
</div>

<div class="page">
    <div style="margin:10px">
        <table width="100%" class="kop">
            <tr>
                <td width="100px" align="center" valign="middle"><img src="<?=base_url('assets/img/logo.png');?>" width="100%"></td>
                <td valign="middle" align="center">
                    <strong><?=$this->session->userdata('company_name01');?></strong><br>
                    <?php
                    echo $this->session->userdata('address_01').' '.$this->session->userdata('address_02').'
                            '.ucwords(strtolower($this->session->userdata('kec_name'))).'
                            '.ucwords(strtolower($this->session->userdata('kab_name'))).'
                            '.ucwords(strtolower($this->session->userdata('prov_name'))).'
                            '.$this->session->userdata('country').' '.$this->session->userdata('postal_code').'<br>
                            <i class="fas fa-phone-alt"></i> '.$this->session->userdata('phone').'
                            <i class="fas fa-envelope"></i> '.$this->session->userdata('email').'
                            <i class="fas fa-globe"></i> '.base_url('');
                    ?>
                </td>
            </tr>
        </table>
        <hr style="margin-bottom:10px">
        <div class="judul">LAPORAN <?=$lap;?></div>
        <table width="100%" class="it-grid">
            <thead>
            <tr>
                <th width="40px"></th>
                <th width=""></th>
                <th width="100px"></th>
                <th width="150px"></th>
                <th width="100px"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="" colspan="3" align="right">Total Tagihan</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($price_all,0,'','.');?></span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Sudah Dibayar</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($price_paid,0,'','.');?></span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Belum Dibayar</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($price_unpaid,0,'','.');?></span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Tagihan Pelanggan Dengan NPWP</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($price_npwp,0,'','.');?></span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Tagihan Pelanggan Tanpa NPWP</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($price_nonnpwp,0,'','.');?></span> </td>
            </tr>
            <tr>
                <td colspan="3" align="right">Total Pajak</td>
                <td align="right"><span class="float-left">Rp.</span> <span class="float-right"><?=number_format($allTax,0,'','.');?></span> </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>--}}
</body>
</html>