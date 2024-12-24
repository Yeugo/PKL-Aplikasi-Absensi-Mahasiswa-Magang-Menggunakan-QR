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
        Schema::table('users', function (Blueprint $table) {
            // Hapus constraint foreign key
            $table->dropForeign(['bidang_id']);

            // Hapus constraint unique pada kolom phone
            $table->dropUnique(['phone']);

            // Hapus kolom
            $table->dropColumn(['name', 'phone', 'bidang_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kembali kolom name
            $table->string('name');

            // Tambahkan kembali kolom phone dengan unique constraint
            $table->string('phone')->unique();

            // Tambahkan kembali kolom bidang_id dengan foreign key
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
        });
    }
};
