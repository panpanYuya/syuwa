<?php

namespace App\Models;

use App\Models\PostTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tag_name',
    ];

    /**
     * 投稿したユーザーの情報を取得する
     */
    public function postTags()
    {
        return $this->hasMany(PostTag::class)->withDefault();
    }
}
