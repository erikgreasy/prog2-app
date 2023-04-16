<?php

namespace App\Diffchecker;

enum OutputType: string
{
    case Json = 'json';
    case Html = 'html';
    case HtmlJson = 'html_json';
}
