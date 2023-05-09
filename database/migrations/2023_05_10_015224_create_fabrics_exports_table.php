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
        Schema::create('fabrics_exports', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            //Время когда был вывоз моделей(тканей) (может быть любое время так как вывоз мог быть вчера, 1 неделю назад итд)
            $table->dateTime('date_time');
            $table->integer('fabric_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('message')->nullable();
            // Время созданя не посредсвенно записи в базе данных, не путать с полем date_time когда был завоз на склад
            $table->timestamps();
        });

        Schema::table('fabrics_exports', function (Blueprint $table) {
            $table->foreign('fabric_id')->references('id')->on('fabrics');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::table('fabrics_exports', function($table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('fabrics_exports');
    }
};
