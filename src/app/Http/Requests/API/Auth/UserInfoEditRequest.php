<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\NameValidation;
use App\Rules\Space;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserInfoEditRequest extends FormRequest
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
            'user_id' => ['required','exists:App\Models\Users\User,id'],
            'user_name' => ['required', 'min:1', 'max:100', new Space, new NameValidation],
            'email' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('users')->ignore(Auth::id()),
                'email:strict,dns,spoof'
            ],
            'password' => ['confirmed'],
        ];
    }
}
