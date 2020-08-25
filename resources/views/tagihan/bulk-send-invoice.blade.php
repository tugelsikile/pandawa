@csrf
<table width="100%" class="table table-bordered">
    <thead>
    <tr>
        <th width="50px">#</th>
        <th width="250px">Nomor Invoice</th>
        <th>Nama Pelanggan</th>
        <th width="150px">Jumlah Tagihan</th>
        <th width="50px">Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($invoices as $key => $invoice)
        <input type="hidden" name="id[]" value="{{$invoice->inv_id}}">
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{$invoice->inv_number}}</td>
            <td>{{$invoice->customer->fullname}}</td>
            <td>Rp. {{format_rp($invoice->price_with_tax)}}</td>
            <td align="center"><i class="fa fa-exclamation text-muted"></i></td>
        </tr>
    @empty
    @endforelse
    </tbody>
</table>

<script>
    /*function bulkSend() {

    }
    $('#ModalForm .modal-footer').html('<button type="button" onclick="bulkSend();return false" class="btn btn-outline-primary"><i class="fa fa-send"></i> Mulai Kirim Email</button>');*/
</script>