<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin,petugas')->only(['index', 'show']);
    }


    public function index()
    {
        Log::info('User role: ' . auth()->user()->role); 

        $produks = Produk::all();

        if (auth()->user()->hasRole('admin')) {
            return view('admin.produk.index', compact('produks'));
        } else {
            return view('petugas.produk.index', compact('produks'));
        }
    }
    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric', // Pastikan harga adalah angka
            'stok' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Menghapus titik (.) pemisah ribuan dari harga
        $harga = str_replace('.', '', $request->harga);

        // Menyimpan data produk
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('produk', 'public');
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $harga, // Simpan harga dalam bentuk angka murni
            'stok' => $request->stok,
            'image' => $imageName
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->route('admin.produk.index')->with('error', 'Gagal menambahkan produk!');
    }
}

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk')); 
    }

    public function update(Request $request, $id)
{
    try {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric', // Pastikan harga adalah angka
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $produk = Produk::findOrFail($id);

        // Menghapus titik (.) pemisah ribuan dari harga
        $harga = str_replace('.', '', $request->harga);

        // Menangani gambar (jika ada)
        if ($request->hasFile('image')) {
            if ($produk->image && Storage::exists('public/' . $produk->image)) {
                Storage::delete('public/' . $produk->image);
            }

            $imageName = $request->file('image')->store('produk', 'public');
            $produk->image = $imageName;
        }

        // Update data produk
        $produk->nama_produk = $request->nama_produk;
        $produk->harga = $harga; // Simpan harga dalam bentuk angka murni
        $produk->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->route('admin.produk.index')->with('error', 'Gagal memperbarui produk!');
    }
}

    public function editStok($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit-stok', compact('produk'));
    }
    

    public function updateStok(Request $request, $id)
    {
        try {
           $request->validate([
            'stok' => 'required|numeric|min:0|max:1000000000000000000000',
            ]);

            $produk = Produk::findOrFail($id);
            $produk->stok = $request->stok;
            $produk->save();

            return redirect()->route('admin.produk.index')->with('success', 'Stok berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.produk.index')->with('error', 'Gagal memperbarui stok!');
        }
    }

    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);

            if ($produk->image) {
                Storage::delete('public/' . $produk->image);
            }

            $produk->delete();

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.produk.index')->with('error', 'Gagal menghapus produk!');
        }
    }
}
