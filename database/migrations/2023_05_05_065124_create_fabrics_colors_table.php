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
        Schema::create('fabrics_colors', function (Blueprint $table) {
            $table->id();
            $table->integer('fabric_id')->unsigned();
            $table->integer('color_id')->unsigned();
        });

        Schema::table('fabrics_colors', function (Blueprint $table) {
            $table->foreign('fabric_id')->references('id')->on('fabrics');
            $table->foreign('color_id')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::table('fabrics_colors', function($table) {
            $table->dropForeign(['fabric_id']);
            $table->dropForeign(['color_id']);
        });
        Schema::dropIfExists('fabrics_colors');
    }
};
