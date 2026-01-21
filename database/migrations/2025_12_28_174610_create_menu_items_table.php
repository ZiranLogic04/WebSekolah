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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_navbar_id')->constrained('menu_navbar')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->string('nama');
            $table->string('slug'); // Slug unik dalam konteks parent/menu
            $table->enum('type', ['group', 'page', 'url']);
            $table->longText('content')->nullable(); // Untuk type 'page'
            $table->string('url')->nullable(); // Untuk type 'url'
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Index untuk performa
            $table->index(['menu_navbar_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
