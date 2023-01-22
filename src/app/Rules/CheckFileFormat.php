<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckFileFormat implements Rule
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
        list($fileInfo) = explode(';', $value);
        // 拡張子を取得
        $extension = explode('/', $fileInfo)[1];
        // $fileDataにある"base64,"を削除する
        $imageFormat = ['png', 'jpg', 'jpeg', 'bmp', 'gif', 'svg', 'webp'];

        for ((int) $i = 0; $i < count($imageFormat); $i++) {
            if ($extension === $imageFormat[$i]) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '画像ファイルのみ投稿できます。';
    }
}
