<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TicketType extends Enum
{
    const General = 0;
    const Reparacion = 1;
    const Derivacion = 2;
}
