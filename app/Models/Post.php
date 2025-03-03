<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const DEFAULT_POSTER_NAME = '名無しさん';

    protected $fillable = [
        'board_id',
        'poster_name',
        'ip_address',
        'body',
        'image_path',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
