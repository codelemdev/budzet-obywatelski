<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->integer('spam_reports')->default(0);
            $table->boolean('is_spam')->default(false);
            $table->boolean('is_violation')->default(false);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->integer('spam_reports')->default(0);
            $table->boolean('is_spam')->default(false);
            $table->boolean('is_violation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->dropColumn(['spam_reports', 'is_spam', 'is_violation']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['spam_reports', 'is_spam', 'is_violation']);
        });
    }
};
