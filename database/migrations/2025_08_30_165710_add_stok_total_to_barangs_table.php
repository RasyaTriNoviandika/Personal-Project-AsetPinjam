<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('barangs', function (Blueprint $table) {
        $table->integer('stok_total')->default(0);
    });
}

public function down(): void
{
    Schema::table('barangs', function (Blueprint $table) {
        $table->dropColumn('stok_total');
    });
}

};
