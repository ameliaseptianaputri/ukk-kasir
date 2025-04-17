@extends('layouts.app')

@section('title', 'Buat Penjualan')

@section('content')
    <h1 class="h3 mb-4 text-dark font-weight-bold">Penjualan</h1>

    <div class="card shadow mb-5" style="height: calc(100vh - 180px); overflow-y: auto;">
        <div class="card-body">
            <div class="row">
                @foreach($produks as $produk)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $produk->image) }}" class="card-img-top"
                                alt="{{ $produk->nama_produk }}" style="height: 150px; object-fit: contain;">

                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $produk->nama_produk }}</h5>
                                <p class="card-text">Stok: {{ $produk->stok }}</p>
                                <p class="card-text">Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                                <div class="d-flex justify-content-center align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm minus-btn"
                                        data-id="{{ $produk->id }}">-</button>
                                    <span class="mx-2 quantity-display" id="jumlah-{{ $produk->id }}">0</span>
                                    <button class="btn btn-outline-secondary btn-sm plus-btn"
                                        data-id="{{ $produk->id }}">+</button>
                                </div>

                                <p class="mt-2">Subtotal: Rp <span id="subtotal-{{ $produk->id }}">0</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tombol Selanjutnya tetap di bawah -->
    <form id="checkoutForm" action="{{ route('penjualan.checkout') }}" method="POST">
        @csrf
        <input type="hidden" name="produk_data" id="produkDataInput">
        <div style="position: fixed; bottom: 20px; left: 0; right: 0; z-index: 999; text-align: center;">
            <button type="submit" class="btn btn-primary px-5 py-2" id="nextButton" disabled>Selanjutnya</button>
        </div>
    </form>


    <!-- Script fungsi + - dan validasi tombol -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const produks = @json($produks);
            const nextButton = document.getElementById('nextButton');
            const jumlahData = {};

            produks.forEach(produk => {
                jumlahData[produk.id] = 0;

                const plusBtn = document.querySelector(`.plus-btn[data-id='${produk.id}']`);
                const minusBtn = document.querySelector(`.minus-btn[data-id='${produk.id}']`);
                const jumlahEl = document.getElementById(`jumlah-${produk.id}`);
                const subtotalEl = document.getElementById(`subtotal-${produk.id}`);

                plusBtn.addEventListener("click", function () {
                    if (jumlahData[produk.id] < produk.stok) {
                        jumlahData[produk.id]++;
                        updateDisplay(produk.id);
                        checkNextButton();
                    }
                });

                minusBtn.addEventListener("click", function () {
                    if (jumlahData[produk.id] > 0) {
                        jumlahData[produk.id]--;
                        updateDisplay(produk.id);
                        checkNextButton();
                    }
                });

                function updateDisplay(id) {
                    jumlahEl.textContent = jumlahData[id];
                    subtotalEl.textContent = (jumlahData[id] * produk.harga).toLocaleString('id-ID');
                }
            });

            function checkNextButton() {
                const anySelected = Object.values(jumlahData).some(val => val > 0);
                nextButton.disabled = !anySelected;
            }

            document.getElementById('checkoutForm').addEventListener('submit', function (e) {
                const selected = [];
                Object.entries(jumlahData).forEach(([id, jumlah]) => {
                    if (jumlah > 0) {
                        const produk = produks.find(p => p.id == id);
                        selected.push({
                            id: produk.id,
                            nama: produk.nama_produk,
                            harga: produk.harga,
                            jumlah: jumlah
                        });
                    }
                });

                document.getElementById('produkDataInput').value = JSON.stringify(selected);
            });


        });
    </script>
@endsection