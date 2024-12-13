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
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npm');
            $table->string('phone')->unique()->nullable();
            $table->string('univ');
            $table->string('alamat');
            $table->foreignId('bidang_id')->constrained('bidangs');
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbing')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peserta');
    }
};
