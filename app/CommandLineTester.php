<?php

namespace App;

use App\Contracts\Tester;
use App\Dto\TesterInput;
use App\Dto\TesterResult;
use Symfony\Component\Process\Process;

class CommandLineTester implements Tester
{
    public function run(TesterInput $input): TesterResult
    {
        if (!$input->testerPath) {
            throw new \Exception('Tester path not set when calling command line tester.');
        }

        $process = new Process(['php', $input->testerPath, json_encode($input)]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Running command line tester was not successful. Error: ' . $process->getErrorOutput());
        }
 
        return TesterResult::fromJson($process->getOutput());
    }
}
