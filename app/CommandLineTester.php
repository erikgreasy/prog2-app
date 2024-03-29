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
        $testerPath = '/home/xmasnye/ctester_json';

        if (!$testerPath) {
            throw new \Exception('Tester path not set when calling command line tester.');
        }

        $encodedTesterData = json_encode($input->toJson());

        info("RUNNING TESTER: {$testerPath} {$encodedTesterData}");

        $output = shell_exec("{$testerPath} {$encodedTesterData}");

        info('Program output: ' . json_encode($output));

        if (!$output) {
            throw new \Exception('Did not get any output from exec.');
        }
//
//        $process = new Process(["{$testerPath} {$encodedTesterData}"]);
//
//        info($process->getCommandLine());
//
//        $process->run();
//
//        if (!$process->isSuccessful()) {
//            throw new \Exception('Running command line tester was not successful. Error: ' . $process->getErrorOutput());
//        }

        return TesterData::fromJson($output);
    }
}
