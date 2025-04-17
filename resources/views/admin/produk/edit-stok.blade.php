@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-dark font-weight-bold">Edit Produk</h1>
    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="{{ route('produk.update-stok', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $produk->nama_produk }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="stok" value="{{ $produk->stok }}" required>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection