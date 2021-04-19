<?php declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface BasketItemContract
{
    public function product(): MorphTo|HasOne|Model;

    public function variant(): MorphTo|HasOne;
}
