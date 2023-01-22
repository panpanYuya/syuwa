<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'img_url' => str_replace(env('AWS_URL') . '/' . env('AWS_BUCKET') . '/', '', 'syuwa-post-img/test.png')
        ];
    }
}
