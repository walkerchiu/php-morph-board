<?php

namespace WalkerChiu\MorphBoard\Models\Entities;

use WalkerChiu\MorphBoard\Models\Entities\Board;
use WalkerChiu\MorphImage\Models\Entities\ImageTrait;

class BoardWithImage extends Board
{
    use ImageTrait;
}
