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
        Schema::table('pembimbing', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('alamat');
            $table->foreignId('user_id')->nullable()->after('bidang_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembimbing', function (Blueprint $table) {
            //
        });
    }
};
