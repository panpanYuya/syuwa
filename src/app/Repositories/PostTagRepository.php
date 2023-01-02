<?php

namespace App\Repositories;

use App\Models\PostTag;
use Illuminate\Database\Eloquent\Collection;

class PostTagRepository implements PostTagInterface
{

    /**
     * タグIdから投稿を取得
     *
     * @param integer $tagId
     * @param integer $numOfDisplaiedPosts
     * @return Collection
     */
    public function sortPostsByTag(int $tagId, int $numOfDisplaiedPosts): Collection
    {
        return PostTag::where('tag_id', $tagId)->with(['post', 'post.images', 'tag', 'post.user'])->offset($numOfDisplaiedPosts)->limit(10)->get();
    }
}
