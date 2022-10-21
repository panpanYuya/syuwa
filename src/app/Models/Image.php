<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'post_id',
        'img_url',
        'tag_id',
    ];

    /**
     * 投稿したユーザーの情報を取得する
     */
    public function post()
    {
        return $this->belongsTo(Post::class)->withDefault();
    }
}
