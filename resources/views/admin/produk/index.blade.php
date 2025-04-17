@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
   @section('content')
    <h1 class="h3 mb-4 text-dark font-weight-bold">Daftar Produk</h1>
    <div class="card border rounded p-4 mb-4">
    <div class="d-flex justify-content-end">
        <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>
    </div>


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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($produk->image)
                                        <img src="{{ asset('storage/' . $produk->image) }}" alt="Gambar {{ $produk->nama_produk }}"
                                            width="150">
                                    @endif
                                </td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>Rp {{ number_format($produk->harga, 2, ',', '.') }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td>
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <!-- Tombol Edit Stok -->
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#editStokModal{{ $produk->id }}">
                                        Edit Stok
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="editStokModal{{ $produk->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="editStokModalLabel{{ $produk->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('produk.update-stok', $produk->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editStokModalLabel{{ $produk->id }}">Edit
                                                            Stok Produk</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Produk</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $produk->nama_produk }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Stok</label>
                                                            <input type="number" class="form-control" name="stok"
                                                                value="{{ $produk->stok }}" required>
                                                            {{-- <input type="number" class="form-control" name="stok" value="{{ $produk->stok }}" required min="0" max="1000000000"> --}}

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update Stok</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="delete-form"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const deleteForms = document.querySelectorAll(".delete-form");

                                            deleteForms.forEach(form => {
                                                form.addEventListener("submit", function (e) {
                                                    e.preventDefault();

                                                    Swal.fire({
                                                        title: "Yakin ingin menghapus?",
                                                        text: "Data yang dihapus tidak bisa dikembalikan!",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#d33",
                                                        cancelButtonColor: "#3085d6",
                                                        confirmButtonText: "Ya, hapus!",
                                                        cancelButtonText: "Batal"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            form.submit();
                                                        }
                                                    });
                                                });
                                            });
                                        });
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection