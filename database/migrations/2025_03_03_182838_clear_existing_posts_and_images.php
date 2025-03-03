<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $posts=Post::whereNotNull('image_path')->get();
        foreach($posts as $post){
            $path = str_replace('storage/', 'public/', $post->image_path);
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        Schema::disableForeignKeyConstraints();
        Post::truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
