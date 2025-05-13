<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('pembimbing_id')->constrained('pembimbing')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->integer('sikap');
            $table->integer('kedisiplinan');
            $table->integer('kesungguhan');
            $table->integer('mandiri');
            $table->integer('kerjasama');
            $table->integer('teliti');
            $table->integer('pendapat');
            $table->integer('hal_baru');
            $table->integer('inisiatif'); 
            $table->integer('kepuasan');
            $table->string('catatan')->nullable();           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai');
    }
};
