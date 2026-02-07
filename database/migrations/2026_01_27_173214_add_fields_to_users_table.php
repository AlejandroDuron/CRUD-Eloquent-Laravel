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
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->after('name');
            $table->string('username')->unique()->after('lastname');

            $table->string('dui')->unique()->after('username');
            $table->string('phone_number', 20)->nullable()->after('dui');
            $table->date('birth_date')->after('phone_number');
            $table->date('hiring_date')->nullable()->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastname');
            $table->dropColumn('username');
            $table->dropColumn('dui');
            $table->dropColumn('phone_number');
            $table->dropColumn('birth_date');
            $table->dropColumn('hiring_date');
        });
    }
};
