<?php

namespace App;

use App\Contracts\Tester;
use App\Dto\TesterData;
use Symfony\Component\Process\Process;

class CommandLineTester implements Tester
{
    public function run(TesterData $input): TesterData
    {
        if (!$input->testerPath) {
            throw new \Exception('Tester path not set when calling command line tester.');
        }

        $process = new Process(['php', $input->testerPath, json_encode($input)]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Running command line tester was not successful. Error: ' . $process->getErrorOutput());
        }

        return TesterData::fromJson($process->getOutput());
    }
}
