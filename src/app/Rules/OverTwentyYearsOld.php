<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

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
     * Determine if the validation rule passes.
     * //TODO 何の為のruleかを記載する
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //お酒の解禁年齢は基本的に変更がないと思われるので、マジックナンバーのまま記述する
        $checkTwenty = date("Y-m-d", strtotime("-20 year"));
        return ['birthday' => "before_or_equal:$checkTwenty"];

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
