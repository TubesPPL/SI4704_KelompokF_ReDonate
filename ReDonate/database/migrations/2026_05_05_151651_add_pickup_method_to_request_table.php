<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request', function (Blueprint $table) {
            $table->string('pickup_method')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('request', function (Blueprint $table) {
            $table->dropColumn('pickup_method');
        });
    }
};