<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\OverTwentyYearsOld;
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
            //TODO アルファベットと英語の判定を作成する
            'user_name' => 'required|min:1|max:255',
            'email' => 'required|min:3|max:255|unique:App\Models\Users\User,email|email:strict,dns,spoof',
            'birthday' => 'required',
            // 'birthday' => new OverTwentyYearsOld,
            'password' => 'required|min:8|max:255|confirmed',
        ];
    }
}
