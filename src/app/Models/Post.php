<?php

namespace App\Models;

use App\Models\Users\User;
use App\Models\Image;
use App\Models\PostTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'text',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'updated_at',
    ];


    /**
     * 投稿したユーザーの情報を取得する
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * 投稿に紐づくタグを取得する
     */
    public function postTags()
    {
        return $this->hasMany(PostTag::class);
    }

    /**
     * 投稿に紐づく画像を取得する
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
