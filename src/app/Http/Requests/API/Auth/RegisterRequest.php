<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\NameValidation;
use App\Rules\OverTwentyYearsOld;
use App\Rules\Space;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_name' => ['required', 'min:1', 'max:255', new Space, new NameValidation],
            'email' => ['required','min:3','max:255', 'unique:App\Models\Users\User,email', 'email:strict,dns,spoof'],
            'birthday' => ['required',new OverTwentyYearsOld],
            'password' => ['required', 'min:8', 'max:255','confirmed', new Space, new NameValidation],
        ];
    }

    /**
     * 入力値
     *
     * @return void
     */
    public function attributes()
    {
        return [
            'user_name' => 'ユーザー名',
            'email' => 'メールアドレス',
            'birthday' => '誕生日',
            'password' => 'パスワード',
        ];
    }
}
