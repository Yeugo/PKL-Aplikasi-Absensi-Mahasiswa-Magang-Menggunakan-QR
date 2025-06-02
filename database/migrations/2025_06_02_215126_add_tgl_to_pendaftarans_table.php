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
        Schema::table('pendaftarans', function (Blueprint $table) {
             // Tambahkan tanggal mulai dan selesai rencana magang
            $table->date('tgl_mulai_magang')->nullable()->after('bidang_id'); // Ganti 'kolom_sebelumnya'
            $table->date('tgl_selesai_magang_rencana')->nullable()->after('tgl_mulai_magang');

            // Status penyelesaian magang
            $table->enum('status_penyelesaian', [
                'Belum Dimulai',
                'Aktif',
                'Selesai',
                'Diberhentikan',
                'Mengundurkan Diri',
            ])->default('Belum Dimulai')->after('tgl_selesai_magang_rencana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('tgl_mulai_magang');
            $table->dropColumn('tgl_selesai_magang_rencana');
            $table->dropColumn('status_penyelesaian');
        });
    }
};
