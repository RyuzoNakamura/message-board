<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PostController;
use Livewire\Attributes\Validate;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::latest()
            ->paginate(10);

        return view('boards.index', compact('boards'));
    }

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'poster_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $board = Board::create([
            'title' => $validated['title'],
            'ip_address' => $request->ip(),
        ]);

        Log::info('新規スレ作成' . $board->id);

        // 1レス目の投稿
        // PostControllerのstoreメソッドを呼び出す
        $postController = app()->make(PostController::class);
        $response = $postController->store($request, $board);

        return redirect()->route('boards.show', $board);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        $posts = $board->posts()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('boards.show', compact('board', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('boards.index');
    }
}
