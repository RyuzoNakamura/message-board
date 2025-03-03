<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'title',
        'status',
        'ip_address',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
