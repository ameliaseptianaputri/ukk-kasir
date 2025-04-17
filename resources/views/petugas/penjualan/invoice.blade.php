@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <a href="#" class="btn btn-secondary">Kembali</a>
            <a href="#" class="btn btn-primary" target="_blank">Unduh Invoice</a>
        </div>

        {{-- Informasi Invoice & Member --}}
        <div class="card p-3 mb-3">
            <h5>Invoice-#</h5>
            <p><strong>Tanggal:</strong> </p>

            <div class="mt-3">
                <h5 class="mb-2">Informasi Member:</h5>
                <ul class="mb-0">
                    <li><strong>Nama Member:</strong></li>
                    <li><strong>No Telepon:</strong></li>
                    <li><strong>Poin Dipakai:</strong></li>
                    <li><strong>Poin Didapat:</strong></li>
                    <li><strong>Poin Sekarang:</strong></li>
                </ul>
            </div>
            <p><strong>Status Pelanggan:</strong> Non Member</p>

        </div>

        {{-- Tabel Produk --}}
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td>Rp </td>
                    <td></td>
                    <td>Rp </td>
                </tr>
            </tbody>
        </table>

        <div class="card p-3 mb-2" style="background-color: #f8f9fa;">
            <p><strong>Kasir:</strong> </p>
            <p><strong>Kembalian:</strong> Rp </p>
        </div>

        <div class="text-right mt-3">
            <div class="p-3" style="background-color: #dee2e6; display: inline-block;">
                <h5><strong>Total:</strong> Rp </h5>
            </div>
        </div>

    </div>
@endsection