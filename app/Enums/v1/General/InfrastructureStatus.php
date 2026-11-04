<?php

namespace App\Enums\v1\General;

enum InfrastructureStatus: int
{
    case IN_STOCK = 1;
    case ON_ROUTE = 2;
    case OPERATIVE = 3;
    case DISCONNECTED = 4;
    case SOLD = 5;
    case STOLEN = 6;
    case UNKNOWN = 7;
    case DAMAGED = 8;
}
