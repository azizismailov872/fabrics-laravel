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
        
        Schema::dropIfExists('fabrics');
        

        Schema::create('fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->integer('quantity')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('material_id')->nullable()->unsigned();
            $table->integer('color_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('fabrics', function (Blueprint $table) {
            $table->foreign('material_id')->references('id')->on('materials')->nullOnDelete();
            $table->foreign('color_id')->references('id')->on('colors')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabrics');
    }
};
