@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
<h1 class="h3 mb-4 text-dark font-weight-bold">Penjualan</h1>

<form method="GET" action="{{ route('petugas.penjualan.index') }}">
    <div class="d-flex justify-content-between align-items-end flex-wrap mb-3">
        
        {{-- KIRI: Input dan tombol --}}
        <div class="d-flex align-items-end flex-wrap" style="gap: 1rem;">
            <div>
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>

            <div>
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    <option value="">-- Pilih Bulan --</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ request('tahun') }}">
            </div>

            {{-- Tombol --}}
            <div class="d-flex align-items-end" style="gap: 0.5rem; margin-left: 1rem;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('petugas.penjualan.index') }}" class="btn btn-secondary">Reset</a>
                <a href="{{ route('penjualan.export', ['tanggal' => request('tanggal'), 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-success">Export</a>
            </div>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('penjualan.create') }}" class="btn btn-primary">Tambah</a>
        </div>
    </div>
</form>



    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualans as $index => $penjualan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($penjualan->status !== 'non' && $penjualan->member)
                                        {{ $penjualan->member->nama }}
                                    @else
                                        Bukan Member
                                    @endif

                                </td>
                                <td>{{ $penjualan->tanggal_penjualan }}</td>
                                <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->user->name ?? '-' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modalDetail{{ $penjualan->id }}">Lihat</a>
                                    <a href="{{ route('penjualan.pdf', $penjualan->id) }}" class="btn btn-sm btn-info">Unduh
                                        Bukti</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

                @foreach ($penjualans as $penjualan)
                    <div class="modal fade" id="modalDetail{{ $penjualan->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="modalDetailLabel{{ $penjualan->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Penjualan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nama Pelanggan:</strong>
                                        {{ $penjualan->status === 'member' && $penjualan->member ? $penjualan->member->nama : 'Bukan Member' }}
                                    </p>
                                    <p><strong>Tanggal Penjualan:</strong> {{ $penjualan->tanggal_penjualan }}</p>
                                    <p><strong>Total Harga:</strong> Rp
                                        {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                                    </p>
                                    <p><strong>Dibuat Oleh:</strong> {{ $penjualan->user->name ?? '-' }}</p>

                                    <hr>
                                    <h5>Detail Produk</h5>
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($penjualan->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->produk->nama_produk }}</td>
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection