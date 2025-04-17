@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Daftar Produk</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Produk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($produk->image)
                                        <img src="{{ asset('storage/' . $produk->image) }}" alt="Gambar {{ $produk->nama_produk }}" width="150">
                                    @endif
                                </td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>Rp {{ number_format($produk->harga, 2, ',', '.') }}</td>
                                <td>{{ $produk->stok }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
