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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade'); // Foreign key ke tabel 'peserta'
            $table->enum('status', [
                'pending',
                'submitted',
                'disetujui_oleh_pembimbing',
                'disetujui_oleh_admin',
                'ditolak_oleh_pembimbing',
                'ditolak_oleh_admin'
            ])->default('pending');
            $table->timestamp('tgl_pengajuan')->nullable();
            $table->timestamp('tgl_disetujui_pembimbing')->nullable();
            $table->timestamp('tgl_disetujui_admin')->nullable();
            $table->text('alasan_ditolak')->nullable(); // Alasan penolakan
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sertifikats');
    }
};
