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
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total Harga</strong>
                                <strong>Rp </strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kanan: Form -->
                <div class="col-md-5">
                    <form action="#" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="produk_data" value="#">
                        <input type="hidden" name="total_harga" value="#">

                        <div class="form-group mt-3">
                            <label>Status Member</label>
                            <select name="status" id="status" class="form-control">
                                <option value="non" >Bukan
                                    Member
                                </option>
                                <option value="member">
                                    Member
                                </option>
                            </select>
                        </div>

                        <div class="form-group mt-3" id="noTelpDiv" style="display: none;">
                            <label>Nomor Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="08xxxx"
                                value="#">
                        </div>

                        <div class="form-group mt-3">
                            <label>Total Bayar</label>
                            <input type="text" id="harga" class="form-control" placeholder="Masukkan total bayar">
                            <input type="hidden" id="harga_hidden" name="total_bayar">
                            <small id="kurangText" class="text-danger" style="display: none;">Jumlah bayar kurang</small>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection