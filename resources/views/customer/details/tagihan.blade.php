<div id="content">
    <table id="tagihan-pelanggan" class="table table-bordered">
        <thead>
        <tr>
            <th width="120px">Tanggal</th>
            <th>Nama Layanan</th>
            <th width="120px">Jumlah Tagihan</th>
            <th width="100px">Status Pembayaran</th>
        </tr>
        </thead>
        <tbody>
        @if($tagihan)
            @foreach($tagihan as $item)
                <tr>
                    <td data-order="{{ $item->inv_date }}">{{ tglIndo($item->inv_date) }}</td>
                    <td>{{ $item->paket->count() > 0 ? $item->paket->pac_name : '' }}</td>
                    <td data-order="{{ $item->price_with_tax }}">Rp. {{ format_rp($item->price_with_tax) }}</td>
                    <td>{{ $item->is_paid == 1 ? 'Lunas' : 'Tunggak' }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <script>
        $('#tagihan-pelanggan').dataTable({});
    </script>
</div>