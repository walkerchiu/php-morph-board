<?php

namespace WalkerChiu\MorphBoard\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class BoardLang extends Lang
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-board.boards_lang');

        parent::__construct($attributes);
    }
}
