<?php

namespace App\Services;

use App\Repositories\PostTagRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class PostTagService
{
    private PostTagRepository $postTagRepository;

    public function __construct(
        PostTagRepository $postTagRepository
    ) {
        $this->postTagRepository = $postTagRepository;
    }

    /**
     * タグIdから投稿を取得
     *
     * @param integer $tagId
     * @param integer $numOfDisplaiedPosts
     * @return Collection
     */
    public function sortPostsByTag(int $tagId, int $numOfDisplaiedPosts): Collection
    {
        try {
            $result = $this->postTagRepository->sortPostsByTag($tagId, $numOfDisplaiedPosts);
        } catch (Exception $e) {
            abort(500);
        }
        return $result;
    }
}
