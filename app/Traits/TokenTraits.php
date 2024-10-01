<?php

namespace App\Traits;

trait TokenTraits
{
    public function expiryTime() : int
    {
        // 5 minutes
        return 5;
    }
}
