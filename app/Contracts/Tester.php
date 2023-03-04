<?php

namespace App\Contracts;

use App\Dto\TesterInput;
use App\Dto\TesterResult;

interface Tester
{
    public function run(TesterInput $input): TesterResult;
}
