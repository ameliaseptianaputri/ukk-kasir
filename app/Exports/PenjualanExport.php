<?php
namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggal, $bulan, $tahun;

    public function __construct($tanggal = null, $bulan = null, $tahun = null)
    {
        $this->tanggal = $tanggal;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = Penjualan::with(['member', 'details.produk', 'user']);

        // Menambahkan filter berdasarkan tanggal, bulan, dan tahun jika ada
        if ($this->tanggal) {
            $query->whereDate('tanggal_penjualan', $this->tanggal);
        }

        if ($this->bulan && $this->tahun) {
            $query->whereMonth('tanggal_penjualan', $this->bulan)
                  ->whereYear('tanggal_penjualan', $this->tahun);
        } elseif ($this->tahun) {
            $query->whereYear('tanggal_penjualan', $this->tahun);
        } elseif ($this->bulan) {
            $query->whereMonth('tanggal_penjualan', $this->bulan);
        }

        return $query->get(); 
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }

    public function map($penjualan): array
    {
        $isMember = $penjualan->status === 'member' && $penjualan->member;

        // Menggabungkan daftar produk yang dibeli dengan informasi qty dan harga
        $produkList = $penjualan->details->map(function ($detail) {
            return $detail->produk->nama_produk . ' ( ' . $detail->qty . ' : Rp. ' . number_format($detail->harga, 0, ',', '.') . ' )';
        })->implode(', ');

        // Mengambil data member atau default "Bukan Member"
        $nama = $isMember ? $penjualan->member->nama : 'Bukan Member';
        $nohp = $isMember ? $penjualan->member->no_hp : '-';
        $poin = $isMember ? $penjualan->member->poin : '-';

        return [
            $nama,
            $nohp,
            $poin,
            $produkList,
            'Rp. ' . number_format($penjualan->total_harga, 0, ',', '.'),
            'Rp. ' . number_format($penjualan->total_bayar ?? $penjualan->total_harga, 0, ',', '.'),
            'Rp. ' . number_format($penjualan->poin_digunakan ?? 0, 0, ',', '.'),
            'Rp. ' . number_format($penjualan->total_kembalian ?? 0, 0, ',', '.'),
            $penjualan->tanggal_penjualan,
        ];
    }
}
