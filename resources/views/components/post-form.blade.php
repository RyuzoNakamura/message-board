<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="post-form w-full mx-auto">
    <div class="wrapper w-3/4 mx-auto">
        @csrf
        @if ($isThread)
            <div class="w-full">
                <input type="text" name="title" placeholder="スレッドタイトル" required class="w-full">
            </div>
        @endif
        <div class="w-full">
            <input type="text" name="poster_name" placeholder="名前" class="w-full">
        </div>
        <div class="w-full">
            <textarea name="body" placeholder="本文" required class="w-full h-48"></textarea>
        </div>

        {{-- 画像添付ボタン --}}
        <div class="form-group w-full">
            <input class="w-full hover:bg-gray-100 p-2 rounded-lg cursor-pointer" type="file" id="image"
                name="image" accept="image/*">
            <div id="image-preview-container" style="display: none;" class="mt-4">
                <img id="image-preview" src="#" alt="プレビュー" class="max-h-[200px]">
            </div>
        </div>

        <div class="w-full">
            <button class="post-form w-full" type="submit">
                {{ $isThread ? 'スレッド作成' : '投稿する' }}
            </button>
        </div>
    </div>
</form>
