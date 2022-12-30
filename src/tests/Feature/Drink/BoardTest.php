<?php

namespace Tests\Feature\Drink;

use App\Models\Image;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\Users\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BoardTest extends TestCase
{

    use RefreshDatabase;

    public function test_show()
    {

        $email = 'test@test.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->getJson('/api/drink/show/0');

        $response->assertJson(
            [
                'post' => [
                    [
                        'id'=> 1,
                        'user_id'=> 1,
                        'text'=> 'この日本酒は純米大吟醸のお酒です。',
                        'created_at'=> '2022-12-16T12:00:00.000000Z',
                        'following_id'=> 1,
                        'followed_id'=> 2,
                        'post_tags'=> [
                            [
                                'id'=> 1,
                                'post_id'=> 1,
                                'tag_id'=> 1,
                                'tag'=> [
                                    'id'=> 1,
                                    'tag_name'=> '日本酒'
                                ]
                            ]
                        ],
                        'images'=> [
                            [
                                'id'=> 1,
                                'post_id'=> 1,
                                'img_url'=> '/assets/images/wine.png'
                            ]
                        ]
                    ],
                    [
                        'id'=> 2,
                        'user_id'=> 1,
                        'text'=> 'このワインは赤です。',
                        'created_at'=> '2022-12-17T12:00:00.000000Z',
                        'following_id'=> 1,
                        'followed_id'=> 2,
                        'post_tags'=> [
                            [
                                'id'=> 2,
                                'post_id'=> 2,
                                'tag_id'=> 2,
                                'tag'=> [
                                    'id'=> 2,
                                    'tag_name'=> 'ワイン'
                                ]
                            ]
                        ],
                        'images'=> [
                            [
                            'id'=> 2,
                                'post_id'=> 2,
                                'img_url'=> '/assets/images/syuwa-logo.png',
                            ]
                        ]
                    ]

                ]
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * 新規投稿機能の正常系テスト
     *
     * @return void
     */
    public function test_add()
    {
        $file = UploadedFile::fake()->image('test.png', 500, 500);
        $fileExtension = $file->getClientOriginalExtension();
        $data = base64_encode(file_get_contents($file));
        $testImage = 'data:image/' . $fileExtension . ';base64,' . $data;

        $testText = 'これは単体テストの為のテキストです';

        Sanctum::actingAs(
            User::factory()->create(),
            ['view-tasks']
        );

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/drink/add', [
            'post_image' => $testImage,
            'post_tag' => 1,
            'comment' => $testText,
        ]);

        $response->assertSessionHasNoErrors();
        self::assertDatabaseHas(Post::class, [
            'user_id' => Auth::id(),
            'text' => $testText,
        ]);
        $post = Post::where('user_id', Auth::id())->where('text', $testText)->first();
        $this->assertDatabaseHas(PostTag::class, ['post_id' => $post->id]);
        $this->assertDatabaseHas(Image::class, [
            'post_id' => $post->id,
        ]);
        $image = Image::where('post_id', $post->id)->first();
        $imageFile = str_replace(env('AWS_URL') . '/'. env('AWS_BUCKET') . '/', '', $image->img_url);
        Storage::disk('s3')->assertExists($imageFile);

    }

    /**
     * 新規投稿機能の異常系テスト
     *
     * @return void
     */
    public function test_add_input_error()
    {

        $file = UploadedFile::fake()->create('test.pdf');
        $fileExtension = $file->getClientOriginalExtension();
        $data = base64_encode(file_get_contents($file));
        $testImage = 'data:image/' . $fileExtension . ';base64,' . $data;

        $testText = 'これは単体テストの為のテキストです';

        Sanctum::actingAs(
            User::factory()->create(),
            ['view-tasks']
        );

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/drink/add', [
            'post_image' => $testImage,
            'post_tag' => 1,
            'comment' => $testText,
        ]);

        $response->assertStatus(422);

    }

    public function test_get_post_detail()
    {

        $post = Post::factory()->for(User::factory()->create())->create();
        $tag = Tag::factory()->create();
        $postTag = PostTag::factory()->state(['post_id' => $post->id, 'tag_id'=> $tag->id])->create();
        $image = Image::factory()->state(['post_id' => $post->id])->create();

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->getJson('/api/drink/detail/' . $post->id);

        $response->assertJson(
            [
                'post' => [
                    'id' => $post->id,
                    'user_id' => $post->user->id,
                    'text' => $post->text,
                    'user' => [
                        'id' => $post->user->id,
                        'user_name' => $post->user->user_name,
                        'email' => $post->user->email,
                    ],
                    'post_tags' => [
                        [
                            'id' => $postTag->id,
                            'post_id' => $postTag->post_id,
                            'tag_id' => $postTag->tag_id,
                            'tag' => [
                                'id' => $tag->id,
                                'tag_name' => $tag->tag_name
                            ]
                        ]
                    ],
                    'images' => [
                        [
                            'id' => $image->id,
                            'post_id' => $image->post_id,
                            'img_url' => $image->img_url
                        ]
                    ]
                ]
            ],
            JSON_UNESCAPED_UNICODE
        );

    }

}
