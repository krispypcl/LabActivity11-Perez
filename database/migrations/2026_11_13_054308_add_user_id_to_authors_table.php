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
        Schema::table('authors', function (Blueprint $table) {
            // nullable: Lab 10 authors (and authors without a login) have no user
            // unique: one user account can only be linked to one author (that's what makes it hasOne)
            // nullOnDelete: if the user is deleted, the author stays but user_id becomes null
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            // undo up() in reverse order: remove the foreign key and unique index first,
            // then the column itself
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
