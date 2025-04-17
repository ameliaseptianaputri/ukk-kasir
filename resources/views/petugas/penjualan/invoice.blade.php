@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('petugas.penjualan.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('penjualan.pdf', $invoiceId) }}" class="btn btn-primary" target="_blank">Unduh Invoice</a>
    </div>

    <div class="card p-3 mb-3">
        <h5>Invoice-#{{ $invoiceId ?? 'Preview' }}</h5>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>

        @if($status === 'member')
            <div class="mt-3">
                <h5 class="mb-2">Informasi Member:</h5>
                <ul class="mb-0">
                    <li><strong>Nama Member:</strong> {{ $nama_member }}</li>
                    <li><strong>No Telepon:</strong> {{ $no_telp }}</li> 
                    <li><strong>Poin Sekarang:</strong> {{ $poinSekarang }}</li>
                </ul>
            </div>
        @else
            <p><strong>Status Pelanggan:</strong> Non Member</p>
        @endif
    </div>

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
            @foreach($produkData as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>{{ $item['jumlah'] }}</td>
                    <td>Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="card p-3 mb-2" style="background-color: #f8f9fa;">
        @if($status === 'member')
            <p><strong>Poin Digunakan:</strong> {{ $poinDipakai }}</p>
        @endif
        <p><strong>Kasir:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Kembalian:</strong> Rp {{ number_format($kembalian, 0, ',', '.') }}</p>
    </div>

    <div class="text-right mt-3">
        <div class="p-3" style="background-color: #dee2e6; display: inline-block;">
            <h5><strong>Total:</strong> Rp {{ number_format($totalHarga, 0, ',', '.') }}</h5>
        </div>
    </div>

</div>
@endsection
