@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Checkout Member</h2>

    <div class="row">
        {{-- KIRI: TABEL PRODUK --}}
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Rp </td>
                        <td>Rp </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <p><strong>Total Harga:</strong> Rp </p>
                <p><strong>Total Bayar:</strong> Rp </p>
            </div>
        </div>

        {{-- KANAN: INFO MEMBER & POIN --}}
        <div class="col-md-4">
            <form action="#" method="POST" class="mt-2">
                @csrf

                {{-- Hidden Input --}}
                <input type="hidden" name="status" value="member">
                <input type="hidden" name="produk_data" value="#">
                <input type="hidden" name="total_harga" value="#">
                <input type="hidden" name="total_bayar" value="#">
                <input type="hidden" name="no_telp" value="#">
                <input type="hidden" name="gunakan_poin" id="input_gunakan_poin" value="0">

                <div class="form-group">
                    <label>Nama Member</label>
                    <input type="text" name="nama_member" class="form-control">
                </div>

                <div class="form-group mt-3">
                    <label>Poin Sekarang (termasuk dari transaksi ini)</label>
                    <input type="text" class="form-control">
                </div>


                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="gunakan_poin">
                    <label class="form-check-label" for="gunakan_poin">Gunakan Poin</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">Selanjutnya</button>
            </form>
        </div>
    </div>
</div>


@endsection