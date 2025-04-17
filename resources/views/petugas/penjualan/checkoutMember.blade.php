@extends('layouts.app')

@section('title', 'Member')

@section('content')
<div class="container">
    <h2 class="mb-4">Checkout Member</h2>

    <div class="card shadow-sm bg-white p-4">
        <div class="row">
            <div class="col-md-8 border-right">
                <h5 class="mb-3">Produk yang Dipilih</h5>
                <table class="table table-bordered mb-3">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produkData as $produk)
                            <tr>
                                <td>{{ $produk['nama'] }}</td>
                                <td>{{ $produk['jumlah'] }}</td>
                                <td>Rp {{ number_format($produk['harga'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($produk['jumlah'] * $produk['harga'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p><strong>Total Harga:</strong> Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                <p><strong>Total Bayar:</strong> Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
            </div>

            <div class="col-md-4">
                <form action="{{ route('penjualan.store') }}" method="POST" class="mt-2">
                    @csrf

                    <input type="hidden" name="status" value="member">
                    <input type="hidden" name="produk_data" value="{{ json_encode($produkData) }}">
                    <input type="hidden" name="total_harga" value="{{ $totalHarga }}">
                    <input type="hidden" name="total_bayar" value="{{ $totalBayar }}">
                    <input type="hidden" name="no_telp" value="{{ $no_telp }}">
                    <input type="hidden" name="gunakan_poin" id="input_gunakan_poin" value="0">

                    <div class="form-group">
                        <label>Nama Member</label>
                        <input type="text" name="nama_member" class="form-control"
                            value="{{ $member->nama ?? '' }}"
                            {{ $member ? 'readonly' : '' }} required>
                        @if(!$member)
                            <small class="text-danger">* Nomor belum terdaftar, nama harus diisi</small>
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <label>Poin Sekarang (termasuk dari transaksi ini)</label>
                        <input type="text" class="form-control"
                            value="{{ $poin + $calculatedPoin }}" readonly>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="gunakan_poin"
                            {{ !$canUsePoin ? 'disabled' : '' }}>
                        <label class="form-check-label" for="gunakan_poin">Gunakan Poin</label>
                        @if(!$canUsePoin)
                            <small class="text-danger d-block">Poin tidak dapat digunakan pada pembelanjaan pertama.</small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-4">Selanjutnya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('gunakan_poin');
        const hiddenInput = document.getElementById('input_gunakan_poin');

        checkbox.addEventListener('change', function () {
            hiddenInput.value = this.checked ? 1 : 0;
        });
    });
</script>
@endsection
