@extends('layouts.app')

@section('title', 'Checkout Penjualan')

@section('content')
    <h1 class="h3 mb-4 text-dark font-weight-bold">Penjualan</h1>
    <div class="container mt-4">
        <div class="card border rounded p-4 mb-4">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <h1 class="h3 mb-4 text-dark font-weight-bold">Produk yang Dipilih</h1>
                    <div class="card">
                        <ul class="list-group list-group-flush">
                            @foreach($produkData as $produk)
                                <li class="list-group-item">
                                    <strong>{{ $produk['nama'] }}</strong><br>
                                    Rp {{ number_format($produk['harga'], 0, ',', '.') }} x {{ $produk['jumlah'] }} =
                                    Rp {{ number_format($produk['jumlah'] * $produk['harga'], 0, ',', '.') }}
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total Harga</strong>
                                <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kanan: Form -->
                <div class="col-md-5">
                    <form action="{{ route('penjualan.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="produk_data" value="{{ json_encode($produkData) }}">
                        <input type="hidden" name="total_harga" value="{{ $totalHarga }}">

                        <div class="form-group mt-3">
                            <label>Status Member</label>
                            <select name="status" id="status" class="form-control">
                                <option value="non" {{ old('status', $status ?? '') === 'non' ? 'selected' : '' }}>Bukan
                                    Member
                                </option>
                                <option value="member" {{ old('status', $status ?? '') === 'member' ? 'selected' : '' }}>
                                    Member
                                </option>
                            </select>
                        </div>

                        <div class="form-group mt-3" id="noTelpDiv" style="display: none;">
                            <label>Nomor Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="08xxxx"
                                value="{{ old('no_telp', $no_telp ?? '') }}">
                        </div>

                        <div class="form-group mt-3">
                            <label>Total Bayar</label>
                            <input type="text" id="harga" class="form-control" placeholder="Masukkan total bayar">
                            <input type="hidden" id="harga_hidden" name="total_bayar">
                            <small id="kurangText" class="text-danger" style="display: none;">Jumlah bayar kurang</small>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const status = document.getElementById('status');
            const noTelpDiv = document.getElementById('noTelpDiv');
            const hargaInput = document.getElementById('harga');
            const hargaHidden = document.getElementById('harga_hidden');
            const kurangText = document.getElementById('kurangText');
            const totalHarga = {{ $totalHarga }};
            const checkoutForm = document.getElementById('checkoutForm');
            const submitBtn = document.getElementById('submitBtn');

            hargaInput.addEventListener('input', function () {
                let raw = this.value.replace(/\D/g, '');
                this.value = raw ? 'Rp ' + (+raw).toLocaleString('id-ID') : '';
                hargaHidden.value = raw;

                if (parseInt(raw || '0') < totalHarga) {
                    kurangText.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    kurangText.style.display = 'none';
                    submitBtn.disabled = false;
                }
            });


            checkoutForm.addEventListener('submit', function (e) {
                const statusValue = document.getElementById('status').value;

                if (statusValue === 'member') {
                    e.preventDefault(); 
                    this.action = "{{ route('penjualan.checkoutMember') }}"; 
                    this.submit(); 
                }
            });


            if (status.value === 'member') {
                noTelpDiv.style.display = 'block';
            }

            status.addEventListener('change', function () {
                if (this.value === 'member') {
                    noTelpDiv.style.display = 'block';
                } else {
                    noTelpDiv.style.display = 'none';
                }
            });

            // Format total bayar
            hargaInput.addEventListener('input', function () {
                let raw = this.value.replace(/\D/g, '');
                this.value = raw ? 'Rp ' + (+raw).toLocaleString('id-ID') : '';
                hargaHidden.value = raw;

                if (parseInt(raw || '0') < totalHarga) {
                    kurangText.style.display = 'block';
                } else {
                    kurangText.style.display = 'none';
                }
            });
        });

    </script>
@endsection