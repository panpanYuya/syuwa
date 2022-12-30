<?php

namespace Database\Factories;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUser>
 */
class FollowUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'following_id' =>  optional(User::inRandomOrder()->first())->id ?? factory(User::class)->create()->id,
            'followed_id' =>  optional(User::inRandomOrder()->first())->id ?? factory(User::class)->create()->id,
        ];
    }
}
