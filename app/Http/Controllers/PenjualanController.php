<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanExport;
use App\Models\DetailPenjualan;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,petugas')->only(['show', 'index']);
    }

    public function index()
    {
        Log::info('User role: ' . auth()->user()->role); // Log role pengguna untuk debugging

        $penjualans = Penjualan::all();

        if (auth()->user()->hasRole('petugas')) {
            return view('petugas.penjualan.index', compact('penjualans'));
        } else {
            return view('admin.penjualan.index', compact('penjualans'));
        }
    }

    public function create()
    {
        $produks = Produk::all(); // ambil semua produk

        return view('petugas.penjualan.create', compact('produks'));
    }
    public function checkout(Request $request)
    {
        $produkData = json_decode($request->produk_data, true);
        $totalHarga = 0;
        $totalBayar = $request->input('total_bayar');
        $request->session()->put('no_telp', $request->no_telp);

        foreach ($produkData as $produk) {
            $totalHarga += $produk['jumlah'] * $produk['harga'];
        }

        return view('petugas.penjualan.checkout', [
            'produkData' => $produkData,
            'totalHarga' => $totalHarga,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
            'totalBayar' => $totalBayar,

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:non,member',
            'no_telp' => 'required_if:status,member',
            'produk_data' => 'required|json',
            'total_bayar' => 'required|numeric',
            'total_harga' => 'required|numeric',
        ]);

        $produkData = json_decode($request->produk_data, true);

        foreach ($produkData as $item) {
            $produk = Produk::find($item['id']);
            if (!$produk) {
                return back()->withErrors(['Produk tidak ditemukan.']);
            }
            if ($produk->stok < $item['jumlah']) {
                return back()->withErrors([
                    "Stok produk {$produk->nama} tidak mencukupi. Stok tersedia: {$produk->stok}, yang diminta: {$item['jumlah']}"
                ]);
            }
        }

        $totalHarga = $request->total_harga;
        $gunakanPoin = $request->input('gunakan_poin') == 1;
        $poinDipakai = 0;
        $poinDariTransaksi = round($totalHarga * 0.01);

        $member = null;
        $isFirstTransaction = false;

        if ($request->status === 'member') {
            $member = Member::where('no_telp', $request->no_telp)->first();

            // Daftarkan member baru jika belum ada
            if (!$member) {
                $request->validate([
                    'nama_member' => 'required|string|max:255',
                ]);

                $member = Member::create([
                    'no_telp' => $request->no_telp,
                    'nama' => $request->nama_member,
                    'poin' => 0,
                ]);
            }

            $totalPoinSebelumTransaksi = $member->poin;
            $poinDariTransaksi = round($totalHarga * 0.01);
            $poinDipakai = 0;
            
            // Transaksi pertama gak boleh pakai poin
            $isFirstTransaction = $member->penjualans()->count() === 0;
            
            if (!$isFirstTransaction && $gunakanPoin) {
                // Gabungkan poin lama + poin dari transaksi SEKARANG
                $totalPoinGabungan = $totalPoinSebelumTransaksi + $poinDariTransaksi;
            
                // Hitung poin yang bisa dipakai
                $poinDipakai = min($totalPoinGabungan, $totalHarga);
                $totalHarga -= $poinDipakai;
            
                // Karena poin langsung habis dipakai
                $member->poin = $totalPoinGabungan - $poinDipakai;
            } else {
                // Kalo gak pake poin, baru tambahin ke member
                $member->poin += $poinDariTransaksi;
            }
            $member->save();
            

        }

        if ($request->total_bayar < $totalHarga) {
            return back()->withErrors(['Total bayar tidak boleh kurang dari total harga.']);
        }

        $kembalian = $request->total_bayar - $totalHarga;

        // Simpan penjualan (tanpa member_id)
        $penjualan = Penjualan::create([
            'tanggal_penjualan' => now(),
            'status' => $request->status,
            'member_id' => $member->id ?? null,
            'total_bayar' => $request->total_bayar,
            'total_harga' => $totalHarga,
            'kembalian' => $kembalian,
            'poin_digunakan' => $poinDipakai,
            'poin_didapat' => $poinDariTransaksi,
            'user_id' => auth()->id(),
        ]);


        // Simpan detail produk
        foreach ($produkData as $item) {
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $item['id'],
                'qty' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $item['jumlah'] * $item['harga'],
            ]);

            // Update stok
            $produk = Produk::find($item['id']);
            $produk->stok -= $item['jumlah'];
            $produk->save();
        }

        // Kirim ke view invoice
        return view('petugas.penjualan.invoice', [
            'invoiceId' => $penjualan->id,
            'tanggal' => $penjualan->tanggal_penjualan,
            'produkData' => $produkData,
            'totalHarga' => $totalHarga,
            'kembalian' => $kembalian,
            'status' => $request->status,
            'nama_member' => $member->nama ?? null,
            'no_telp' => $member->no_telp ?? null,
            'poinDipakai' => $poinDipakai,
            'poinDapat' => $poinDariTransaksi,
            'poinSekarang' => $member->poin ?? null,
        ]);
    }


    public function unduhBukti($id)
    {
        $penjualan = Penjualan::with('details.produk')->findOrFail($id);

        // Ambil data member berdasarkan no_telp (jika statusnya member)
        $member = null;
        if ($penjualan->status === 'member') {
            $member = $penjualan->member;
        }

        // Hitung kembalian
        $hargaSetelahPoin = $penjualan->total_harga - ($penjualan->poin_digunakan ?? 0);
        $kembalian = $penjualan->total_bayar - $hargaSetelahPoin;

        $data = [
            'penjualan'       => $penjualan,
            'status'          => $penjualan->status,
            'nama_pelanggan'  => $member ? $member->nama : 'Bukan Member',
            'no_telp'         => $member ? $member->no_telp : '-',
            'bergabung_sejak' => $member ? $member->created_at->format('d-m-Y') : '-',
            'poin_member'     => $member ? $member->poin : 0,
            'kembalian'       => $kembalian,
            'tanggal'         => $penjualan->created_at->format('d-m-Y'),
            'kasir'           => auth()->user()->name,
        ];

        $pdf = Pdf::loadView('petugas.penjualan.pdf', $data);

        return $pdf->download('bukti_penjualan_' . $penjualan->id . '.pdf');
    }

    public function checkoutMember(Request $request)
    {
        $request->validate([
            'produk_data' => 'required|json',
            'total_harga' => 'required|numeric',
            'total_bayar' => 'required|numeric',
        ]);

        $produkData = json_decode($request->produk_data, true);
        $totalHarga = $request->total_harga;
        $totalBayar = $request->total_bayar;
        $noTelp = $request->no_telp;

        $kembalian = $totalBayar - $totalHarga;

        $request->session()->put('no_telp', $noTelp);

        $member = Member::where('no_telp', $noTelp)->first();

        $isFirstPurchase = !$member;
        $poin = $member ? $member->poin : 0;
        $calculatedPoin = round($totalHarga * 0.01);
        $canUsePoin = !$isFirstPurchase;

        return view('petugas.penjualan.checkoutMember', [
            'produkData' => $produkData,
            'totalHarga' => $totalHarga,
            'totalBayar' => $totalBayar,
            'kembalian' => $kembalian,
            'no_telp' => $noTelp,
            'member' => $member,
            'poin' => $poin,
            'calculatedPoin' => $calculatedPoin,
            'canUsePoin' => $canUsePoin,
            'isFirstPurchase' => $isFirstPurchase,
            'status' => 'member',
            'nama_member' => $member->nama ?? '',
        ]);
    }

    public function dashboard()
    {
        // Hitung jumlah penjualan yang terjadi hari ini (jumlah record penjualan)
        $jumlahPenjualanHariIni = Penjualan::whereDate('tanggal_penjualan', today())->count();

        // Tanggal terakhir diperbarui
        $terakhirDiperbarui = today()->toDateString();

        // Kirim data ke view
        return view('petugas.dashboard', [
            'jumlahPenjualanHariIni' => $jumlahPenjualanHariIni,
            'terakhirDiperbarui' => $terakhirDiperbarui,
        ]);
    }

    public function dashboardAdmin()
    {
        // Data untuk Bar Chart Penjualan Harian
        $penjualanHarian = DB::table('penjualans')
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as jumlah'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $labels = $penjualanHarian->pluck('tanggal');
        $data = $penjualanHarian->pluck('jumlah');

        // Data untuk Pie Chart Penjualan Produk
        $produk = DB::table('detail_penjualans')
            ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
            ->select('produks.nama_produk', DB::raw('SUM(qty) as total'))
            ->groupBy('produks.nama_produk')
            ->orderByDesc('total')
            ->get();

        $produkLabels = $produk->pluck('nama_produk');
        $produkData = $produk->pluck('total');

        // Warna random untuk masing-masing produk
        $produkColors = [
            '#3A59D1',
            '#7AC6D2'
        ];
        return view('admin.dashboard', compact(
            'labels',
            'data',
            'produkLabels',
            'produkData',
            'produkColors'
        ));
    }
//     public function export(Request $request)
// {
//     // Ambil filter dari query string
//     $tanggal = $request->input('tanggal'); // format: YYYY-MM-DD
//     $bulan = $request->input('bulan');     // format: 1 - 12
//     $tahun = $request->input('tahun');     // format: YYYY

//     return Excel::download(new PenjualanExport($tanggal, $bulan, $tahun), 'penjualan.xlsx');
// }
// public function export(Request $request)
// {
//     $tanggal = $request->input('tanggal');
//     $bulan = $request->input('bulan');
//     $tahun = $request->input('tahun');

//     return Excel::download(new PenjualanExport($tanggal, $bulan, $tahun), 'penjualan.xlsx');
// }

}
