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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
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
