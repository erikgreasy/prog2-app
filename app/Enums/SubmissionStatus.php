<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Created = 'created';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
}
