<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'post_id',
        'tag_id',
    ];

    /**
     * 投稿したユーザーの情報を取得する
     */
    public function post()
    {
        return $this->belongsTo(Post::class)->withDefault();
    }

    /**
     * 投稿したユーザーの情報を取得する
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class)->withDefault();
    }
}
