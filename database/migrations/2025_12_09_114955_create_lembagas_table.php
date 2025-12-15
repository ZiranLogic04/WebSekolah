<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lembagas', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_sekolah');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('akreditasi', 2)->nullable(); // A, B, C
            $table->string('npsn')->nullable();
            $table->enum('status', ['Negeri', 'Swasta'])->default('Negeri');
            $table->string('kepala_sekolah')->nullable();
            $table->string('logo')->nullable(); // bisa simpan path logo
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembagas');
    }
};
