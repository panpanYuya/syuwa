<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Carbon\Carbon;

class OverTwentyYearsOld implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 20歳以上であるかを判定
     * 20歳以上true
     * 20歳未満false
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //お酒の解禁年齢は基本的に変更がないと思われるので、マジックナンバーのまま記述する
        $checkTwenty = date("Y-m-d", strtotime($value));
        $dt = new Carbon();
        $dt->format('Y-m-d');
        $dt->subYear(20);
        return $dt->gt($checkTwenty);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'このアプリケーションは20歳以上でないとご使用できません。';
    }
}
