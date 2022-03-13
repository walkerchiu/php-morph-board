<?php

namespace WalkerChiu\MorphBoard\Models\Entities;

use WalkerChiu\MorphBoard\Models\Entities\Board;
use WalkerChiu\MorphImage\Models\Entities\ImageTrait;
use WalkerChiu\MorphLink\Models\Entities\LinkTrait;

class BoardWithImageAndLink extends Board
{
    use ImageTrait;
    use LinkTrait;
}
