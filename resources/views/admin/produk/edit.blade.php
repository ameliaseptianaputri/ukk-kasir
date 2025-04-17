@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <h1 class="h3 mb-4 text-dark font-weight-bold">Edit Produk</h1>
    <div class="card border rounded p-4 mb-4">
        <div class="card-body">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                value="{{ $produk->nama_produk }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga <span class="text-danger">*</span></label>
                            <input type="text" name="harga_display" class="form-control" id="harga" name="harga" value="{{ $produk->harga }}"
                                required>
                            <input type="hidden" name="harga" id="harga_hidden">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" value="{{ $produk->stok }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="image">Gambar Produk</label>
                        @if ($produk->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $produk->image) }}" width="150" class="mb-2">
                            </div>
                        @endif
                        <div class="input-group">
                            <input type="text" class="form-control bg-white" placeholder="Tidak ada file yang dipilih"
                                id="file-name" readonly>
                            <div class="input-group-append">
                                <button class="btn" type="button" id="btn-file"
                                    style="background-color: #e0e0e0; color: #333;">
                                    Pilih File
                                </button>
                            </div>
                        </div>
                        <input type="file" id="image" name="image" class="d-none">
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnFile = document.getElementById('btn-file');
            const inputFile = document.getElementById('image');
            const fileNameField = document.getElementById('file-name');
            const inputHarga = document.getElementById('harga');
            const hiddenHarga = document.getElementById('harga_hidden');

            inputHarga.addEventListener('input', () => {
                let raw = inputHarga.value.replace(/\D/g, '');
                inputHarga.value = raw ? 'Rp ' + (+raw).toLocaleString('id-ID') : '';
                hiddenHarga.value = raw;
            });

            btnFile.addEventListener('click', function () {
                inputFile.click();
            });

            inputFile.addEventListener('change', function () {
                const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
                fileNameField.value = fileName;
            });
        });
    </script>
@endsection
