<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan')->nullable(); 
            $table->enum('status', ['member', 'non']);
            $table->unsignedBigInteger('member_id')->nullable(); 
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null'); 
            $table->date('tanggal_penjualan');
            $table->unsignedBigInteger('total_harga');
            $table->unsignedBigInteger('total_bayar');  
            $table->unsignedBigInteger('kembalian')->nullable();
            $table->integer('poin_digunakan')->nullable();
            $table->integer('poin_didapat')->default(0);
            $table->unsignedBigInteger('user_id'); // kasir
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
