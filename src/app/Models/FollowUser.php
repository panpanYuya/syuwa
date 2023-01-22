<?php

namespace App\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'following_id',
        'followed_id',

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
    public function user()
    {
        return $this->belongsTo(
                    User::class,
                    ['user_id','following_id'],
                    ['user_id', 'followed_id'],
                )->withDefault();
    }
}
