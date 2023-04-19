<?php

namespace App;

use App\Contracts\Tester;
use App\Dto\TesterData;
use App\Dto\TesterInput;
use App\Dto\TesterInputCase;
use App\Dto\TesterInputScenario;
use App\Dto\TesterResult;
use App\Dto\TesterCase;
use App\Dto\TesterScenario;
use Illuminate\Support\Facades\Log;

class DummyTester implements Tester
{
    public function run(TesterData $input): TesterData
    {
        return new TesterData(
            $input->id,
            $input->src,
            $input->scenarios
        );
    }
}
