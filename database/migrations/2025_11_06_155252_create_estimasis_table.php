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
    Schema::create('cost_estimates', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category')->nullable();
        $table->text('description');
        $table->decimal('min_price', 10, 2);
        $table->decimal('max_price', 10, 2);
        $table->string('delivery_time');
        $table->json('features');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimasis');
    }
};
