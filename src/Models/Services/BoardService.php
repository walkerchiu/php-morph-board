<?php

namespace WalkerChiu\MorphBoard\Models\Services;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Services\CheckExistTrait;

class BoardService
{
    use CheckExistTrait;

    protected $repository;



    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = App::make(config('wk-core.class.morph-board.boardRepository'));
    }
}
