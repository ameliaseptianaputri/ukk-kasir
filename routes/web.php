<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Exports\PenjualanExport;
use App\Http\Controllers\MemberController;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login'); 
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/penjualan/{id}/unduh-bukti', [PenjualanController::class, 'unduhBukti'])->name('penjualan.pdf');
    Route::get('penjualan/export-excel', [PenjualanController::class, 'export'])->name('penjualan.export');
    // Route::get('/penjualan/export', [PenjualanController::class, 'export'])->name('penjualan.export');

});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/produk', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/admin/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/admin/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/admin/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/admin/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/admin/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('/admin/produk/{id}/edit-stok', [ProdukController::class, 'editStok'])->name('produk.edit-stok');
    Route::put('/admin/produk/{id}/update-stok', [ProdukController::class, 'updateStok'])->name('produk.update-stok');
    
    Route::get('/admin/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/admin/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/admin/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/admin/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/admin/penjualan', [PenjualanController::class, 'index'])->name('admin.penjualan.index');
    Route::get('/admin/dashboard', [PenjualanController::class, 'dashboardAdmin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/produk', [ProdukController::class, 'index'])->name('petugas.produk.index');
    Route::get('/petugas/penjualan', [PenjualanController::class, 'index'])->name('petugas.penjualan.index');
    Route::get('/petugas/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/petugas/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
    Route::post('/petugas/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::post('/petugas/penjualan/checkoutMember', [PenjualanController::class, 'checkoutMember'])->name('penjualan.checkoutMember');    
    Route::get('/get-member', [MemberController::class, 'getMemberByPhone']);
    Route::get('/petugas/dashboard', [PenjualanController::class, 'dashboard'])->name('petugas.dashboard');
});


