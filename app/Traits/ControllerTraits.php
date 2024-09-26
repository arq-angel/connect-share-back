<?php

namespace App\Traits;

trait ControllerTraits
{
    public function debuggable() : Bool
    {
        return true;
    }

    public function perPageLimit() : int
    {
        return 15;
    }
}
