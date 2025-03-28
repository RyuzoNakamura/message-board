<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PostController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->indexOrm();
    }
    private function indexOrm()
    {
        $boards = Board::latest()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('boards.index', compact('boards'));
    }
    private function indexSql()
    {
        $limit = 10;
        $offset = 0;
        $boards = DB::select('SELECT * FROM boards ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?', [$limit, $offset]);

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
     * スレの新規作成と1レス目の投稿を行う
     */
    public function store(Request $request)
    {
        return $this->storeOrmVer($request);
    }

    private function storeOrmVer(Request $request)
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

    private function storeSqlVer(Request $request)
    {
        // 1レス目のバリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'poster_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // スレの新規作成
        DB::insert(
            'INSERT INTO boards(title , ip_address, created_at, updated_at)' . ' VALUES(?, ?, ?, ?)',
            [$validated['title'], $request->ip(), now(), now()]
        );
        $boardID = DB::getPdo()->lastInsertId();

        Log::info('新規スレ作成' . $boardID);

        // 1レス目を作成したスレに投稿
        $board = DB::selectOne(
            'SELECT * FROM boards WHERE id = ? LIMIT 1',
            [$boardID]
        );
        $postController = app()->make(PostController::class);
        $response = $postController->store($request, $board);

        return redirect()->route('boards.show', $board);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        return $this->showOrmVer($board);
    }

    private function showOrmVer(Board $board)
    {
        $posts = $board->posts()
            ->orderBy('id', 'asc')
            ->get();

        return view('boards.show', compact('board', 'posts'));
    }

    private function showSqlVer(Board $board)
    {
        $boardID = $board->id;
        $posts = DB::select(
            'SELECT *
            FROM posts
            WHERE posts.board_id = ?
            AND posts.board_id IS NOT null
            ORDER BY created_at ASC',
            [$boardID]
        );

        // 日付を Carbon インスタンスに変換
        foreach ($posts as $post) {
            $post->created_at = Carbon::parse($post->created_at);
            $post->updated_at = Carbon::parse($post->updated_at);
        }

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
        $this->destroySqlVer($board);

        return redirect()->route('boards.index');
    }
    private function destroyOrmVer(Board $board)
    {
        $board->delete();
    }
    private function destroySqlVer(Board $board)
    {
        DB::delete('DELETE FROM boards WHERE id = ?', [$board->id]);
    }

    public function activity(Request $request)
    {
        $this->activitySqlVer($request);
    }
    private function activityOrmVer(Request $request)
    {
        $ip = $request->ip();
        $boards = Board::where('ip_address', $ip)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $posts = Post::where('ip_address', $ip)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('activities.index', compact('posts', 'boards'));
    }
    private function activitySqlVer(Request $request)
    {
        $ip = $request->ip();
        $boards = DB::select('SELECT * FROM boards WHERE ip_address = ? ORDER BY id DESC LIMIT 10', [$ip]);
        $posts = DB::select('SELECT * FROM posts WHERE ip_address = ? ORDER BY id DESC LIMIT 20', [$ip]);

        $boards = Board::hydrate($boards);
        $posts = Post::hydrate($posts);

        return view('activities.index', compact('posts', 'boards'));
    }
}
