<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\Space;
use Illuminate\Foundation\Http\FormRequest;

class PasswordCompleteRequest extends FormRequest
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
            'token' => ['required'],
            'password' => ['required', 'min:8', 'max:255', 'confirmed', new Space, 'alpha_num'],
        ];
    }
}