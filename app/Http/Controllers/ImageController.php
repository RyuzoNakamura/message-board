<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class ImageController extends Controller
{
    /**
     * 画像をアップロードして保存する
     * @return string 保存した画像のパス
     */
    public function store(Request $request, Board $board, $postNumber)
    {
        if (!request()->hasFile('image')) {
            return null;
        }

        $directory = "images/{$board->id}";

        // ディレクトリが存在しなければ作成
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $extension = $request->file('image')->getClientOriginalExtension(); // 拡張子を取得
        $filename = "{$postNumber}.{$extension}"; // ファイル名はレス番号
        $path = $request->file('image')->storeAs($directory, $filename); // ファイルを保存

        return "storage/{$path}"; // 保存したファイルのパスを返す
    }
    /**
     * 画像を表示する
     */
    public function show($board_id, $post_number)
    {
        $post = Post::where('board_id', $board_id)
            ->skip($post_number - 1)
            ->take(1)
            ->first();

        // レコードが存在しない場合は404
        if (!$post || !$post->image_path) {
            abort(404);
        }

        // 画像ファイルのパスを取得
        $path = str_replace('storage/', 'public/', $post->image_path);

        // ファイルが存在しなければ404
        if (!Storage::exists($path)) {
            abort(404);
        }

        return response()->file(storage_path('app/' . $path));
    }

    /**
     * 画像を削除する
     */
    public function destroy($imagePath)
    {
        if ($imagePath) {
            $path = str_replace('storage/', 'public/', $imagePath);
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }
}
