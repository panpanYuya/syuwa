<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PostTagInterface
{
    public function sortPostsByTag(int $tagId, int $numOfDisplaiedPosts): Collection;
}
