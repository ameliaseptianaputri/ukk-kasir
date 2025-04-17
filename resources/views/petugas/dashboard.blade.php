@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card w-100 shadow-sm rounded-4" style="max-width: 800px;">
        <div class="card-body text-center px-5 py-4">
            <h4 class="mb-4 fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h4>

            <div class="bg-light rounded-3 py-5 px-3">
                <h5 class="mb-4 text-muted">Total Penjualan Hari Ini</h5>
                <div class="display-4 fw-bold text-black bg-white rounded py-3">{{ $jumlahPenjualanHariIni }}</div>
                <p class="mt-3 text-black bg-light py-2 rounded">
                    Jumlah total penjualan yang terjadi hari ini.
                </p>
            </div>

            <div class="mt-4 text-muted small">
                Terakhir diperbarui: {{ $terakhirDiperbarui }}
            </div>
        </div>
    </div>
</div>
@endsection
