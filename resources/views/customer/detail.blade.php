@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Customer</div>
            <div class="card-body">
                <form id="FormTable">
                    <table class="table table-bordered" id="dataTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="min-mobile"><input type="checkbox" onclick="tableCbxAll(this)"></th>
                            <th class="min-mobile">Nama Pelanggan</th>
                            <th class="min-desktop">Alamat</th>
                            <th class="min-desktop">Produk</th>
                            <th class="min-desktop">Tagihan Bulan Ini</th>
                            <th class="min-desktop">Status</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <script>

    </script>

@endsection
