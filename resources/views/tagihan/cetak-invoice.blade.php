<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cetak Laporan</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/cetak.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/chandara.css') }}">

    <style>
        *{
            font-family: 'CANDARA' !important; font-size:12pt !important;
            line-height:15px;
        }
        .kolom{
            width:45%;
        }
        .kolom div, .kolom table td{ font-size:12pt !important; }
        .align-right{ text-align: right }
        .company-name{ font-size:16pt !important;}
        .inv_number{
            font-size:20pt !important; color: #2b669a; clear: both; margin:10px auto;
        }
        .invtable{
            border-bottom:solid 3px #CCC;
        }
        .invtable th{
            border-bottom:solid 3px #CCC; padding-bottom:10px;
        }
        .invtable td{
            padding:10px 5px;
        }
        .invtable tr:nth-child(even){
            background: #e8e8e8
        }
        .qrcode{
            float: right; margin-top:100px;
        }
        .inv_footer{clear:both;position:absolute;left:30px;right:30px;bottom:20px;}
        .terms,.inv_footer{font-size:10pt !important;}
    </style>

</head>
<body>
<div class="page">
    <div class="align-right" style="margin-bottom:20px">
        <img src="{{ asset('images/logo.png') }}">
    </div>
    <div class="kolom" style="float:left">
        <span>Kepada :</span>
        <div><strong class="company-name">{{ $data->customer->fullname }}</strong> </div>
        <div>{{ $data->customer->address_01 }} {{ $data->customer->address_02 }}</div>
        <div>{{ dataKec($data->customer->district_id)->name }} {{ dataKab($data->customer->regency_id)->name }}</div>
        <div>{{ dataProv($data->customer->province_id)->name }} {{ $data->customer->postal_code }}</div>
        <div>Indonesia</div>
    </div>
    <div class="kolom align-right" style="float: right">
        <div><strong class="company-name">{{ $companyInfo->company_name01 }}</strong></div>
        <div>{{ $companyInfo->address_01 }}</div>
        <div>{{ dataKec($companyInfo->district_id)->name }} {{ dataKab(dataKec($companyInfo->district_id)->regency_id)->name }} {{ $companyInfo->postal_code }}</div>
        <div>Indonesia</div>
        <div style="height:20px"></div>
        <div>Phone : {{ $companyInfo->phone }}</div>
        <table width="100%" style="margin-top:20px">
            <tr>
                <td width="" align="right">Nomor Pelanggan</td>
                <td width="5px">:</td>
                <td align="right" width="200px">{{ $data->customer->kode }}</td>
            </tr>
            <tr>
                <td width="" align="right">Tanggal Invoice</td>
                <td width="5px">:</td>
                <td align="right" width="200px">{{ tglIndo($data->inv_date) }}</td>
            </tr>
            <tr>
                <td align="right">Batas Waktu</td>
                <td>:</td>
                <td align="right">{{ tglIndo($data->due_date) }}</td>
            </tr>
        </table>
    </div>
    <div class="inv_number">INVOICE {{ $data->inv_number }}</div>
    <table width="100%" class="invtable" style="margin-top:30px">
        <thead>
        <tr>
            <th width="150px">Produk / Layanan</th>
            <th>Deskripsi Produk / Layanan</th>
            <th width="50px">Qty</th>
            <th width="150px" align="right">Harga</th>
            <th width="190px" align="right">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $data->paket->pac_name }}</td>
            <td>{{ $data->paket->description }}</td>
            <td>1</td>
            <td align="right">{{ format_rp($data->paket->price) }}</td>
            <td align="right">{{ format_rp($data->paket->price) }}</td>
        </tr>
        <tr>
            <td colspan="4" align="right">SubTotal</td>
            <td align="right">Rp. {{ format_rp($data->price) }}</td>
        </tr>
        @if($data->customer->npwp==1)
            <tr>
                <td colspan="4" align="right">Pajak</td>
                <td align="right">Rp. {{ format_rp($data->price_tax) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="4" align="right"><strong>Total</strong></td>
            <td align="right"><strong>Rp. {{ format_rp($data->price_with_tax) }}</strong></td>
        </tr>
        </tbody>
    </table>
    <div class="terms" style="float:left;margin-top:30px;line-height:12px;">
        <strong>Terms</strong><br>
        {!! $companyInfo->terms !!}
    </div>
    <div class="qrcode qrcode_{{ $data->inv_id }}"></div>
    <div class="inv_footer" style="line-height:12px;">
        {!! $companyInfo->footer !!}
    </div>
</div>
</body>
</html>