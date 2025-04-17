@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Daftar User</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Aksi</th.
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>

                                <td>
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="delete-form"
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
                                                    e.preventDefault(); // Mencegah submit otomatis

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
                                                            form.submit(); // Jika dikonfirmasi, baru form dikirim
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