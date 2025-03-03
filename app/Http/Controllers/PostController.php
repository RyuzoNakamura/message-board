<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ImageController;

class PostController extends Controller
{
    protected $imageController;
    public function __construct(ImageController $imageController)
    {
        $this->imageController = $imageController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board)
    {

        Log::info('リクエストデータ:', $request->all());  // 全リクエストデータを記録
        Log::info('image_path値:', ['value' => $request->image_path]);  // image_pathの値を記録

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
            'poster_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // 投稿数をカウントしてレス番号を決定
        $postNumber = Post::where('board_id', $board->id)->count() + 1;
        $posterName = $request->input('poster_name');

        $post = new Post([
            'board_id' => $board->id,
            'poster_name' => $posterName !== null && trim($posterName) !== '' ? $posterName : Post::DEFAULT_POSTER_NAME,
            'body' => $request->input('body'),
            'ip_address' => $request->ip(),
        ]);

        if ($request->hasFile('image')) {
            $post->image_path = $this->imageController->store($request, $board, $postNumber);
        }

        $post->save();

        return redirect()->route('boards.show', $board);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->image_path) {
            $this->imageController->destroy($post->image_path);
        }
        // $board = $post->board;
        $post->delete();

        // return redirect()->route('boards.show', $board);
        return redirect()->back();
    }
}
