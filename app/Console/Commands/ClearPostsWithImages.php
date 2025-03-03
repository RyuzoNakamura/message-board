<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ClearPostsWithImages extends Command
{
    /**
     * コマンド名
     *
     * @var string
     */
    protected $signature = 'posts:clear';

    /**
     * コマンドの説明
     *
     * @var string
     */
    protected $description = '既存の投稿と画像をすべて削除します';

    /**
     * コマンドの実行
     */
    public function handle()
    {
        if (!$this->confirm('すべての投稿と画像を削除しますか？この操作は元に戻せません')) {
            $this->info('操作をキャンセルしました');
            return;
        }

        // データベース上の投稿と関連画像の削除
        $this->deletePostsFromDatabase();

        // storage/app/public/images ディレクトリのクリーンアップ
        $this->cleanupImagesDirectory();

        $this->info("処理が完了しました。");
    }

    /**
     * データベースからの投稿と関連画像の削除
     */
    private function deletePostsFromDatabase()
    {
        $this->info('データベース上の投稿と関連画像を削除しています...');

        // プログレスバーの準備
        $posts = Post::whereNotNull('image_path')->get();
        $count = count($posts);

        if ($count > 0) {
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $deletedImages = 0;

            foreach ($posts as $post) {
                // storageパスを内部パスに変換
                $path = str_replace('storage/', 'public/', $post->image_path);
                if (Storage::exists($path)) {
                    Storage::delete($path);
                    $deletedImages++;
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("{$deletedImages}個の関連画像を削除しました。");
        } else {
            $this->info('削除対象の画像付き投稿はありませんでした。');
        }

        // 投稿テーブルをトランケート
        Schema::disableForeignKeyConstraints();
        Post::truncate();
        Schema::enableForeignKeyConstraints();
        $this->info('すべての投稿をデータベースから削除しました。');
    }

    /**
     * 画像ディレクトリのクリーンアップ
     */
    private function cleanupImagesDirectory()
    {
        $this->info('画像ディレクトリをクリーンアップしています...');

        if (Storage::exists('public/images')) {
            // ディレクトリ内のすべてのファイルとサブディレクトリを取得
            $files = Storage::allFiles('public/images');
            $count = count($files);

            if ($count > 0) {
                $this->info("{$count}個のファイルを削除します...");

                // ディレクトリごと削除して再作成する方法
                Storage::deleteDirectory('public/images');
                Storage::makeDirectory('public/images');

                $this->info("画像ディレクトリをクリアしました。");
            } else {
                $this->info('画像ディレクトリは既に空です。');
            }
        } else {
            $this->info('画像ディレクトリが存在しないため、新規作成します。');
            Storage::makeDirectory('public/images');
        }
    }
}
