<?php

namespace App;

use App\Contracts\Tester;
use App\Dto\TesterData;
use App\Models\Submission;
use Symfony\Component\Process\Process;

class CommandLineTester implements Tester
{
    public function run(TesterData $input): TesterData
    {
        $testerPath = '/home/ploi/ctester_json';

        if (!$testerPath) {
            throw new \Exception('Tester path not set when calling command line tester.');
        }

        $process = new Process([$testerPath, json_encode($input)]);

        info($process->getCommandLine());

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Running command line tester was not successful. Error: ' . $process->getErrorOutput());
        }

        return TesterData::fromJson($process->getOutput());
    }
}
