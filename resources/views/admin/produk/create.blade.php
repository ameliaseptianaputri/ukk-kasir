@extends('layouts.app')

@section('title', 'Create Produk')

@section('content')
    <h1 class="h3 mb-4 text-dark font-weight-bold">Produk</h1>

    <div class="card border rounded p-4 mb-4">
        <div class="card-body">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control" id="nama_produk"
                                placeholder="Masukkan nama produk" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Gambar Produk <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-white" placeholder="Tidak ada file yang dipilih"
                                    id="file-name" readonly>
                                <div class="input-group-append">
                                    <button class="btn" type="button" id="btn-file"
                                        style="background-color: #e0e0e0; color: #333;">Pilih File</button>
                                </div>
                            </div>
                            <input type="file" id="image" name="image" class="d-none" required>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga <span class="text-danger">*</span></label>
                            <input type="text" name="harga_display" class="form-control" id="harga"
                                placeholder="Masukkan harga produk" required>
                            <input type="hidden" name="harga" id="harga_hidden">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" id="stok"
                                placeholder="Masukkan stok produk" required>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputHarga = document.getElementById('harga');
            const hiddenHarga = document.getElementById('harga_hidden');

            inputHarga.addEventListener('input', () => {
                let raw = inputHarga.value.replace(/\D/g, '');
                inputHarga.value = raw ? 'Rp ' + (+raw).toLocaleString('id-ID') : '';
                hiddenHarga.value = raw;
            });

            const inputFile = document.getElementById('image');
            const fileLabel = document.getElementById('fileLabel');

            inputFile.addEventListener('change', function () {
                const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
                fileLabel.textContent = fileName;
            });
            document.getElementById('btn-file').addEventListener('click', function () {
                document.getElementById('image').click();
            });

            document.getElementById('image').addEventListener('change', function () {
                const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
                document.getElementById('file-name').value = fileName;
            });
        });
    </script>
@endsection