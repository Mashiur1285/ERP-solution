<?php

namespace App\Enums;

enum SalesStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
