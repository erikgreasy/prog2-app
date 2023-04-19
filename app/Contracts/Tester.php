<?php

namespace App\Contracts;

use App\Dto\TesterData;
use App\Dto\TesterInput;
use App\Dto\TesterResult;

interface Tester
{
    public function run(TesterData $input): TesterData;
}
