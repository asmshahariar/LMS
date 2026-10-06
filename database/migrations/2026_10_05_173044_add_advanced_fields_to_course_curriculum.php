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
        Schema::table('course_chapters', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
        });

        Schema::table('course_chapter_lessions', function (Blueprint $table) {
            $table->json('materials')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_chapters', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('course_chapter_lessions', function (Blueprint $table) {
            $table->dropColumn('materials');
        });
    }
};
