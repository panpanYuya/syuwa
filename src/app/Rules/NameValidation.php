<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NameValidation implements Rule
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
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        // return preg_match('/^[ぁ-んァ-ン０-９a-zA-Z0-9!-\/:-@¥\[-`\{-~]+$/', $value);
        return preg_match('/^[ぁ-んァ-ン０-９a-zA-Z0-9!-\/:-@¥\[-`\{-~].*$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '入力された文字に使用できない文字がございます。';
    }
}
