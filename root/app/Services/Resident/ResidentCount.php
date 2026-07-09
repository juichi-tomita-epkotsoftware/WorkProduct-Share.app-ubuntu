<?php

namespace App\Services\Resident;

use App\Models\Resident;

class ResidentCount
{
    public function getCount()
    {
        return Resident::count();
    }
}
