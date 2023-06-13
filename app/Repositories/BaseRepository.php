<?php

namespace App\Repositories;

abstract class BaseRepository
{
    public static function make(): static
    {
        return new static();
    }
}
