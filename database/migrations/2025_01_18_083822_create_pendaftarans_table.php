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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npm')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('univ');
            $table->string('alamat');
            $table->foreignId('bidang_id')->constrained('bidangs');
            $table->string('surat_pengantar');
            $table->string('dokumen_lain')->nullable();
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
        Schema::dropIfExists('pendaftarans');
    }
};
