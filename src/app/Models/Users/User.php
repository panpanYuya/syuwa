<?php

namespace App\Models\Users;

use App\Models\Post;
use App\Models\Users\UserPersonalInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];


    //TODO 不要だった場合は削除
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    /**
     * ユーザーの追加情報を取得する
     *
     * @return void
     */
    public function userPersonalInfo()
    {
        return $this->hasOne(UserPersonalInfo::class);
    }


    /**
     * ユーザーに紐づく投稿を取得する
     *
     * @return void
     */
    public function posts()
    {
        return $this->hasMany(Post:: class);
    }
}
