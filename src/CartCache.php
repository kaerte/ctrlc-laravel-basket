<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

interface CartCache
{
    public function get(): Cart;

    public function set(): Cart;
}
