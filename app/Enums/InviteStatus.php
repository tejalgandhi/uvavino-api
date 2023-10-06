<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

Enum InviteStatus: int {
    use InvokableCases;
    use Values;

case PENDING = 0;
case ACCEPTED = 1;
case REJECTED = 2;

    public function label(): string
    {
        return match($this){
        self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected'
        };
    }

}


