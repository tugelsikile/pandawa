<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cetak Laporan</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/cetak.min.css') }}" rel="stylesheet">

    <style>

    </style>

</head>
<body>
<?php $nomor = 1; ?>
@if($customers->count()>0)
    @foreach($customers as $keyPage => $dataPage)
        <div class="page">
            <strong>TUNGGAKAN TAGIHAN</strong>
            <table class="it-grid it-cetak" width="100%">
                <thead>
                <tr>
                    <th width="50px">No.</th>
                    <th>ID / Nama Pelanggan</th>
                    <th width="120px">Nama Cabang</th>
                    <th width="150px">Bulan Tunggakan</th>
                    <th width="120px">Jml Total Tunggakan</th>
                </tr>
                </thead>
                <tbody>
                @if($dataPage->count()>0)
                    @foreach($dataPage as $keyCust => $dataCust)
                        <tr>
                            <td align="center">{{ $nomor }}</td>
                            <td><em>{{ $dataCust->kode }}</em> <strong>{{ $dataCust->fullname }}</strong></td>
                            <td style="white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">{{ $dataCust->cabang->cab_name }}</td>
                            <td>
                                @foreach($dataCust->tagihan as $tagihan)
                                    {{ bulanIndo(date('m',strtotime($tagihan->inv_date))) }},
                                @endforeach
                            </td>
                            <td align="right">Rp. {{ format_rp($dataCust->tagihan->sum('price_with_tax')) }}</td>
                        </tr>
                        <?php $nomor++; ?>
                    @endforeach
                @endif
                </tbody>
                @if($keyPage+1===count($customers))
                    <tfoot>
                    <tr>
                        <th colspan="4">Grand Total</th>
                        <th>{{ $total_tagihan }}</th>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @endforeach
@endif
</body>
</html>