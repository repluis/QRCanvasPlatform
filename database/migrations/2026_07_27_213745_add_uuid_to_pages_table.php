<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->uuid('uuid')
                ->default(DB::raw('gen_random_uuid()'))
                ->unique()
                ->after('id');
        });

        DB::statement('UPDATE "pages" SET uuid = gen_random_uuid() WHERE uuid IS NULL;');
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
