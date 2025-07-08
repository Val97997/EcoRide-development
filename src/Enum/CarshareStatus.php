<?php

namespace App\Enum;
// This Enum will allow us to set up the different possible situations in which a route can find itself, by defining its status
enum CarshareStatus:string
{
    case WAITING = 'waiting';
    case IN_PROGRESS = 'in_progress';
    case COMPLETE = 'complete';
    case CANCELED = 'canceled';
}