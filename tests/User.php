<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests;

use Ctrlc\Cart\Traits\Cartable;
use Ctrlc\Cart\Traits\Productable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Cartable, HasFactory, Notifiable, Productable;

    protected $guarded = [];

    protected $table = 'users';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
