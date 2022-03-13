<?php

namespace WalkerChiu\MorphBoard\Models\Repositories;

use WalkerChiu\MorphBoard\Models\Repositories\BoardRepository;
use WalkerChiu\MorphComment\Models\Repositories\CommentRepositoryTrait;

class BoardRepositoryWithComment extends BoardRepository
{
    use CommentRepositoryTrait;
}
