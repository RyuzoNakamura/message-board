<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;

class BoardPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createBoardOrm();
    }

    private function createBoardOrm(): void
    {
        $faker = Faker::create('ja_JP');

        // スレッドを10件作成
        $boardCount = 10;
        $boards = [];
        for ($i = 1; $i <= $boardCount; $i++) {
            $board = Board::create([
                'title' => $faker->realText(20),
                'status' => 'open',
                'ip_address' => $faker->ipv4,
            ]);
            $boards[] = $board->id;
        }

        // レスをランダムな個数作成
        foreach ($boards as $boardId) {
            $postCount = rand(1, 10);
            for ($i = 1; $i <= $postCount; $i++) {
                $postName = rand(1, 10) > 1
                    ? Post::DEFAULT_POSTER_NAME
                    : $faker->lastName() . $faker->firstName();

                // 画像を添付するかどうかをランダムに決定
                $hasImage = rand(1, 5) === 1;
                $imagePath = null;

                if ($hasImage) {
                    // 画像パスを設定（データベースに保存するパス）
                    $imagePath = 'storage/images/' . $boardId . '/' . $i . '.jpg';

                    // 保存先ディレクトリを作成
                    Storage::disk('public')->makeDirectory('images/' . $boardId);

                    // ランダムなサンプル画像を選択してコピー
                    $sampleImageNumber = rand(1, 5);
                    $sourcePath = public_path('dummy_images/sample_' . $sampleImageNumber . '.jpg');
                    $destinationPath = storage_path('app/public/images/' . $boardId . '/' . $i . '.jpg');

                    if (file_exists($sourcePath)) {
                        copy($sourcePath, $destinationPath);
                    }
                }

                Post::create([
                    'board_id' => $boardId,
                    'poster_name' => $postName,
                    'body' => $faker->realText(100),
                    'ip_address' => $faker->ipv4,
                    'image_path' => $imagePath,
                ]);
            }
        }
    }
    private function createBoardSql(): void
    {
        $faker = Faker::create('ja_JP');

        $boardCount = 10;
        $boards = [];

        // スレを作成
        for ($i = 1; $i <= $boardCount; $i++) {
            DB::insert('INSERT INTO boards (title, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
                $faker->realText(20),
                'open',
                $faker->ipv4,
                Carbon::now(),
                Carbon::now(),
            ]);
        }
        $boardId = DB::getPdo()->lastInsertId();
        $boards[] = $boardId;

        // レスをランダムな個数作成
        foreach ($boards as $boardId) {
            $postCount = rand(1, 10);
            for ($i = 1; $i <= $postCount; $i++) {
                $postName = rand(1, 10) > 1
                    ? Post::DEFAULT_POSTER_NAME
                    : $faker->lastName() . $faker->firstName();


                $hasImage = rand(1, 5) === 1;
                $imagePath = null;

                if ($hasImage) {
                    // 画像パスを「storage/images/スレID/レス番号.jpg」の形式で設定
                    $imagePath = 'storage/images/' . $boardId . '/' . $i . '.jpg';

                    // 実際のファイルシステムにディレクトリを作成
                    Storage::disk('public')->makeDirectory('images/' . $boardId);

                    Storage::disk('public')->copy('dummy_images/sample.jpg', 'images/' . $boardId . '/' . $i . '.jpg');
                }

                DB::insert(
                    'INSERT INTO posts (board_id, poster_name, body, ip_address, image_path, created_at, updated_at) VALUES (?,?,?,?,?,?,?)',
                    [
                        $boardId,
                        $postName,
                        $faker->realText(100),
                        $faker->ipv4,
                        $imagePath,
                        Carbon::now(),
                        Carbon::now(),
                    ]
                );
            }
        }
    }
}
