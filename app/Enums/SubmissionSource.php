<?php

namespace App\Enums;

enum SubmissionSource: string
{
    case MANUAL = 'manual';
    case VCS = 'vcs';
}
