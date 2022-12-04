<?php

namespace App\Http\Requests\API\Drink;

use Illuminate\Foundation\Http\FormRequest;

class PostAddRequest extends FormRequest
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
            //
            'tag' => ['required', 'numeric'],
            'img' => [],
            'comment' => ['max:255'],
        ];
    }
}
