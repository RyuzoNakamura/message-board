# Laravel で匿名掲示板をつくる <!-- omit in toc -->

## 目次 <!-- omit in toc -->

- [1. このプロジェクトについて](#1-このプロジェクトについて)
- [2. 環境](#2-環境)
- [3. 構成](#3-構成)
  - [3.1. モデル](#31-モデル)
  - [3.2. ビュー](#32-ビュー)
  - [3.3. コントローラ](#33-コントローラ)
- [4. 環境構築](#4-環境構築)
  - [4.1. 事前準備](#41-事前準備)
- [5. プロジェクトのセットアップ](#5-プロジェクトのセットアップ)

## 1. このプロジェクトについて

スレッドの作成、スレッドへのレスが可能な匿名掲示板です。

ベンチマークサイト: [あにまん掲示板](https://bbs.animanch.com/board/4627358/)

## 2. 環境

Laravel Sail を使用しています。

```bash
- Laravel: 12.0.1
- PHP: 8.4.4
- Composer: 2.8.6
- MySPL: 8.0.32
- redis: 7.4.2
- Node.js: 22.14.0
- NPM: 11.1.0
```

## 3. 構成

### 3.1. モデル

-   **Board**
    -   各スレッド
    -   id、タイトル、IP アドレス を所持
-   **Post**
    -   各レス
    -   id、board_id、投稿者名、IP アドレス、本文、画像パス を所持

Board と Post は 1 対多関係

### 3.2. ビュー

-   **Board.index**
    -   スレッド一覧
-   **Board.show**
    -   スレッド

### 3.3. コントローラ

-   **BoardController**
    -   index, store, show, destroy を提供
-   **PostController**
    -   store を提供
-   **ImageController**
    -   PostController の store 時に、画像の格納、パスの提供部分を担当

## 4. 環境構築

### 4.1. 事前準備

-   docker desktop のインストール
-   wsl 等の linux 環境 が入っている
    -   mac/linux についてはわからないです

## 5. プロジェクトのセットアップ

1. プロジェクトを作りたい場所に`cd`
2. 以下のコマンドを実行(コピペ Enter で動きます)

```bash
# リポジトリのクローン
git clone https://github.com/RyuzoNakamura/message-board.git
cd message-board

# composerが入っているdockerコンテナをインストール
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# .envファイルの準備
cp .env.example .env

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail shell
chown -R sail:sail storage
chmod -R 775 storage
./vendor/bin/sail npm run dev
```

3.  http://localhost/ にアクセス
