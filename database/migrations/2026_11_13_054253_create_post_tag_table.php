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
        // pivot table: singular names in alphabetical order (post_tag, not tag_post)
        Schema::create('post_tag', function (Blueprint $table) {
            // each row links one post to one tag; deleting either side removes the link
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            // composite primary key instead of an id column: the pair (post_id, tag_id)
            // must be unique, so a post can't have the same tag twice
            $table->primary(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
