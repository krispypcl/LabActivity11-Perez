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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // foreignId + constrained() = unsigned big integer column with a foreign key to posts.id
            // cascadeOnDelete: deleting a post also deletes its comments
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            // the resident who wrote the comment; deleting the user deletes their comments
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body'); // text instead of string because comments can be up to 1000 chars
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments'); // undo up() by dropping the whole table
    }
};
