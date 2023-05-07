<?php

namespace App\Console\Commands;

use App\Actions\ProcessAssignmentWithTester;
use App\Actions\ResolveSubmissionFolder;
use App\Actions\StoreSubmission;
use App\Dto\StoreSubmissionDto;
use App\Dto\TesterData;
use App\Enums\SubmissionSource;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RunTestOnFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run_test_on_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        config(['database.connections.mysql.strict' => false]);

        $parsedData = $this->prepareStudentDataFromFiles();
        $assignment = $this->createTestedAssignment();

        Submission::query()->delete();

        $parsedData->each(function (array $item) use ($assignment) {
            // create user if not exist
            $user = User::updateOrCreate([
                'email' => $item['email']
            ], [
                'first_name' => 'Tester',
                'last_name' => 'Testovaci',
                'full_name' => 'Tester Testovaci',
                'username' => $item['username'],
                'password' => bcrypt('randomPassword123'),
            ]);

            $dto = new StoreSubmissionDto(
                assignmentId: $assignment->id,
                userId: $user->id,
                ip: '127.0.0.1',
                source: SubmissionSource::MANUAL,
                try: $item['try'],
            );

            $submission = app(StoreSubmission::class)->execute($dto);

            $relativeDiskPath = app(ResolveSubmissionFolder::class)->handle($submission);

            $filePath = $item['file_path'];


            $submission->update([
                'file_path' => $filePath,
                'file_content' => file_get_contents($filePath),
            ]);

            app(ProcessAssignmentWithTester::class)
                ->onQueue()
                ->execute(
                    $submission,
                    new TesterData(
                        $submission->id,
                        $filePath,
                        $assignment->getTesterScenariosArray(),
                    ),
                    $assignment->tester_path,
                );
        });

        return Command::SUCCESS;
    }

    private function prepareStudentDataFromFiles(): Collection
    {
        $files = File::files(Storage::path('z2_src'));//Storage::files('z2_src');

        return collect($files)->map(function (\Symfony\Component\Finder\SplFileInfo $file) {
            $splitName = explode('_', $file->getFilenameWithoutExtension());

            return [
                'username' => $splitName[0],
                'email' => $splitName[0] . '@stubatest.sk',
                'try' => $splitName[3],
                'file_path' => $file->getPathname()
            ];
        })->sortBy('try');
    }

    private function createTestedAssignment(): Assignment
    {
        $oldAssignmentAssignment = Assignment::where('slug', str()->slug('Zadanie č. 2 - Konské dostihy'))->first();

        $oldAssignmentAssignment?->submissions()->delete();

        $oldAssignmentAssignment?->delete();


        $assignment = Assignment::create([
            'title' => 'Zadanie č. 2 - Konské dostihy',
            'slug' => str()->slug('Zadanie č. 2 - Konské dostihy'),
            'excerpt' => 'Cieľom zadania je napísať program na simuláciu prekážkových konských dostihov. Na začiatku sa vygenerujú pozície prekážok na trati. Pohyb koní na trati je riadený náhodnosťou - hod kockou. Počas preteku môže kôň skočiť na prekážku, ktorá spôsobí penalizáciu alebo elimináciu z pretekov. Na záver sa vyhodnotí poradie, v akom kone dobehli do cieľa. Zmyslom zadania je precvičiť si prácu s poliami.',
            'points' => 10,
            'tester_path' => 'path',
            'vcs_branch' => '02',
            'deadline' => now()->addWeek(),
            'published_at' => now()->subWeek(),
            'tries' => [
                ['max_points' => 10],
                ['max_points' => 9],
                ['max_points' => 7],
                ['max_points' => 5],
                ['max_points' => 3],
                ['max_points' => 1],
            ]
        ]);

        $this->createScenarios($assignment);

        return $assignment;
    }

    private function createScenarios(Assignment $assignment): void
    {
        $this->createScenario1($assignment);
        $this->createScenario2($assignment);
        $this->createScenario3($assignment);
        $this->createScenario4($assignment);
        $this->createScenario5($assignment);
        $this->createScenario6($assignment);
        $this->createScenario7($assignment);
        $this->createScenario8($assignment);
        $this->createScenario9($assignment);
        $this->createScenario10($assignment);
        $this->createScenario11($assignment);
    }

    private function createScenario1(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Kontrola chybovej situácie E1 - dĺžka trate.',
            'points' => 0.5,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '5 1 1',
            'stdout' => 'E1',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '300 14 56',
            'stdout' => 'E1',
        ]);
    }

    private function createScenario2(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Kontrola chybovej situácie E2 - počet prekážok.',
            'points' => 0.5,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '66 40 4',
            'stdout' => 'E2',
        ]);
    }

    private function createScenario3(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Kontrola chybovej situácie E3 - rozmiestnenie prekážok na trati.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '12 4 9',
            'stdout' => 'E3',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '53 15 8',
            'stdout' => 'E3',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '98 40 16',
            'stdout' => 'E3',
        ]);
    }

    private function createScenario4(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Výpis vstupných parametrov.',
            'points' => 0.5,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '37 11 4',
            'stdout' => '2 37 11 4',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=10',
            'stdin' => '77 7 7',
            'stdout' => '10 77 7 7',
        ]);

        $scenario->cases()->create([
            'stdin' => '25 4 98',
            'stdout' => '4 25 4 98',
        ]);
    }

    private function createScenario5(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Vygenerovanie dostihovej trate.',
            'points' => 1.5,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=3',
            'stdin' => '20 6 55',
            'stdout' => '01000100100010110000',
        ]);

        $scenario->cases()->create([
            'stdin' => '52 25 9',
            'stdout' => '0011101001101011001011000010010001110110101001011010',
        ]);

        $scenario->cases()->create([
            'stdin' => '85 31 965',
            'stdout' => '0011000111000110000110000010000000100110110010010000101101110101000001000100101001100',
        ]);
    }

    private function createScenario6(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Dostihy - kone nestúpia na prekážku (resp. počet prekážok na trati bude 0).',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '14 2 65',
            'stdout' => '  1   1  -1   1   1
  1   2  -1   6   6
  2   1   1   5   6
  2   2   6   3   9
  3   1   6   4  10
  3   2   9   3  12
  4   1  10   6  16 *
  4   2  12   6  18 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=3',
            'stdin' => '10 1 6',
            'stdout' => '  1   1  -1   1   1
  1   2  -1   3   3
  1   3  -1   5   5
  2   1   1   4   5
  2   2   3   6   9
  2   3   5   1   6
  3   1   5   3   8
  3   2   9   3  12 *
  3   3   6   2   8
  4   1   8   5  13 *
  4   3   8   4  12 *',
        ]);

        $scenario->cases()->create([
            'stdin' => '11 1 85',
            'stdout' => '  1   1  -1   3   3
  1   2  -1   2   2
  1   3  -1   1   1
  1   4  -1   2   2
  2   1   3   3   6
  2   2   2   4   6
  2   3   1   6   7
  2   4   2   3   5
  3   1   6   1   7
  3   2   6   5  11
  3   3   7   4  11
  3   4   5   3   8
  4   1   7   1   8
  4   2  11   6  17 *
  4   3  11   3  14 *
  4   4   8   5  13 *
  5   1   8   2  10
  6   1  10   3  13 *',
        ]);
    }

    private function createScenario7(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Dostihy - kone stúpia len na NORMAL prekážku.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=5',
            'stdin' => '32 8 96',
            'stdout' => '  1   1  -1   2   3 N
  1   2  -1   2   3 N
  1   3  -1   1   1
  1   4  -1   6   6
  1   5  -1   6   6
  2   1   -   -   -
  2   2   -   -   -
  2   3   1   4   6 N
  2   4   6   2   8
  2   5   6   5  11
  3   1   3   3   6
  3   2   3   1   4
  3   3   -   -   -
  3   4   8   1   9
  3   5  11   1  13 N
  4   1   6   3   9
  4   2   4   2   6
  4   3   6   5  11
  4   4   9   2  11
  4   5   -   -   -
  5   1   9   4  13
  5   2   6   5  11
  5   3  11   5  16
  5   4  11   5  16
  5   5  13   4  18 N
  6   1  13   6  19
  6   2  11   4  16 N
  6   3  16   6  22
  6   4  16   6  22
  6   5   -   -   -
  7   1  19   3  22
  7   2   -   -   -
  7   3  22   3  25
  7   4  22   6  29 N
  7   5  18   1  19
  8   1  22   1  23
  8   2  16   2  18
  8   3  25   2  27
  8   4   -   -   -
  8   5  19   2  21
  9   1  23   3  27 N
  9   2  18   4  22
  9   3  27   1  29 N
  9   4  29   5  34 *
  9   5  21   1  22
 10   1   -   -   -
 10   2  22   1  23
 10   3   -   -   -
 10   5  22   1  23
 11   1  27   5  32
 11   2  23   2  25
 11   3  29   6  35 *
 11   5  23   4  27
 12   1  32   3  35 *
 12   2  25   5  30
 12   5  27   3  30
 13   2  30   4  34 *
 13   5  30   3  33 *',
        ]);

        $scenario->cases()->create([
            'stdin' => '65 3 11',
            'stdout' => '  1   1  -1   6   6
  1   2  -1   4   4
  1   3  -1   3   3
  1   4  -1   1   1
  2   1   6   4  10
  2   2   4   1   5
  2   3   3   2   5
  2   4   1   3   4
  3   1  10   3  13
  3   2   5   4   9
  3   3   5   5  10
  3   4   4   4   8
  4   1  13   3  16
  4   2   9   5  14
  4   3  10   6  16
  4   4   8   5  13
  5   1  16   3  19
  5   2  14   6  20
  5   3  16   5  21
  5   4  13   4  17
  6   1  19   4  23
  6   2  20   6  26
  6   3  21   5  26
  6   4  17   3  20
  7   1  23   6  29
  7   2  26   1  27
  7   3  26   5  31
  7   4  20   2  22
  8   1  29   3  32
  8   2  27   3  30
  8   3  31   6  38 N
  8   4  22   3  25
  9   1  32   1  33
  9   2  30   6  36
  9   3   -   -   -
  9   4  25   4  29
 10   1  33   6  39
 10   2  36   6  42
 10   3  38   4  42
 10   4  29   1  30
 11   1  39   5  44
 11   2  42   5  47
 11   3  42   1  43
 11   4  30   2  32
 12   1  44   5  49
 12   2  47   3  50
 12   3  43   5  48
 12   4  32   2  34
 13   1  49   6  55
 13   2  50   3  53
 13   3  48   1  49
 13   4  34   6  40
 14   1  55   6  61
 14   2  53   4  57
 14   3  49   2  51
 14   4  40   2  42
 15   1  61   3  64
 15   2  57   6  63
 15   3  51   6  57
 15   4  42   4  46
 16   1  64   5  69 *
 16   2  63   2  65
 16   3  57   4  61
 16   4  46   1  47
 17   2  65   2  67 *
 17   3  61   1  62
 17   4  47   3  50
 18   3  62   6  68 *
 18   4  50   4  54
 19   4  54   2  56
 20   4  56   3  59
 21   4  59   2  61
 22   4  61   5  66 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=8',
            'stdin' => '14 3 3',
            'stdout' => '  1   1  -1   6   6
  1   2  -1   6   6
  1   3  -1   1   1
  1   4  -1   1   1
  1   5  -1   5   6 N
  1   6  -1   2   2
  1   7  -1   2   2
  1   8  -1   6   6
  2   1   6   3   9
  2   2   6   5  12 N
  2   3   1   1   2
  2   4   1   4   6 N
  2   5   -   -   -
  2   6   2   3   6 N
  2   7   2   2   4
  2   8   6   4  10
  3   1   9   6  15 *
  3   2   -   -   -
  3   3   2   1   4 N
  3   4   -   -   -
  3   5   6   3   9
  3   6   -   -   -
  3   7   4   2   6
  3   8  10   3  13
  4   2  12   2  14
  4   3   -   -   -
  4   4   6   5  12 N
  4   5   9   3  12
  4   6   6   4  10
  4   7   6   6  12
  4   8  13   6  19 *
  5   2  14   2  16 *
  5   3   4   2   6
  5   4   -   -   -
  5   5  12   5  17 *
  5   6  10   1  12 N
  5   7  12   4  16 *
  6   3   6   5  12 N
  6   4  12   4  16 *
  6   6   -   -   -
  7   3   -   -   -
  7   6  12   3  15 *
  8   3  12   2  14
  9   3  14   2  16 *',
        ]);
    }

    private function createScenario8(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Dostihy - kone stúpia len na FATAL prekážku.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'stdin' => '12 3 753',
            'stdout' => '  1   1  -1   4   4
  1   2  -1   2   2
  1   3  -1   5   5 F
  1   4  -1   2   2
  2   1   4   6  10
  2   2   2   3   5 F
  2   4   2   3   5 F
  3   1  10   1  11
  4   1  11   3  14 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '16 4 5',
            'stdout' => '  1   1  -1   2   2 F
  1   2  -1   5   5
  2   2   5   2   7
  3   2   7   6  13
  4   2  13   6  19 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '15 5 102',
            'stdout' => '  1   1  -1   4   4
  1   2  -1   4   4
  2   1   4   2   6 F
  2   2   4   4   8
  3   2   8   3  11
  4   2  11   1  12
  5   2  12   4  16 *',
        ]);
    }

    private function createScenario9(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Dostihy - kone stúpia na obidva typy prekážok: NORMAL aj FATAL.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=3',
            'stdin' => '27 7 56',
            'stdout' => '  1   1  -1   3   3
  1   2  -1   5   5
  1   3  -1   4   4
  2   1   3   6  10 N
  2   2   5   3   8
  2   3   4   4   8
  3   1   -   -   -
  3   2   8   5  13
  3   3   8   6  14
  4   1  10   2  12
  4   2  13   3  16 F
  4   3  14   6  21 N
  5   1  12   6  18
  5   3   -   -   -
  6   1  18   2  21 N
  6   3  21   1  23 N
  7   1   -   -   -
  7   3   -   -   -
  8   1  21   5  26
  8   3  23   1  24
  9   1  26   4  30 *
  9   3  24   2  26
 10   3  26   2  28 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=6',
            'stdin' => '49 14 100',
            'stdout' => '  1   1  -1   5   5
  1   2  -1   2   2
  1   3  -1   3   3
  1   4  -1   3   3
  1   5  -1   6   6
  1   6  -1   5   5
  2   1   5   3   8
  2   2   2   4   6
  2   3   3   2   5
  2   4   3   4   8 N
  2   5   6   1   8 N
  2   6   5   4   9
  3   1   8   5  13
  3   2   6   1   8 N
  3   3   5   1   6
  3   4   -   -   -
  3   5   -   -   -
  3   6   9   2  11
  4   1  13   5  18
  4   2   -   -   -
  4   3   6   2   8
  4   4   8   2  11 N
  4   5   8   4  13 N
  4   6  11   1  13 N
  5   1  18   5  23
  5   2   8   1   9
  5   3   8   5  13
  5   4   -   -   -
  5   5   -   -   -
  5   6   -   -   -
  6   1  23   4  27
  6   2   9   4  13
  6   3  13   2  15
  6   4  11   6  17
  6   5  13   1  14
  6   6  13   4  17
  7   1  27   4  31
  7   2  13   5  18
  7   3  15   4  19
  7   4  17   6  23
  7   5  14   2  16
  7   6  17   6  23
  8   1  31   5  36
  8   2  18   1  19
  8   3  19   4  23
  8   4  23   1  24
  8   5  16   5  21
  8   6  23   5  28 F
  9   1  36   3  40 N
  9   2  19   4  23
  9   3  23   2  26 N
  9   4  24   1  26 N
  9   5  21   4  26 N
 10   1   -   -   -
 10   2  23   5  28 F
 10   3   -   -   -
 10   4   -   -   -
 10   5   -   -   -
 11   1  40   4  44
 11   3  26   3  29 F
 11   4  26   4  31 N
 11   5  26   3  29 F
 12   1  44   3  48 N
 12   4   -   -   -
 13   1   -   -   -
 13   4  31   2  33
 14   1  48   4  52 *
 14   4  33   3  36
 15   4  36   1  37
 16   4  37   5  42
 17   4  42   2  44
 18   4  44   5  49
 19   4  49   4  53 *',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=6',
            'stdin' => '49 14 100',
            'stdout' => '  1   1  -1   5   5
  1   2  -1   2   2
  1   3  -1   3   3
  1   4  -1   3   3
  1   5  -1   6   6
  1   6  -1   5   5
  2   1   5   3   8
  2   2   2   4   6
  2   3   3   2   5
  2   4   3   4   8 N
  2   5   6   1   8 N
  2   6   5   4   9
  3   1   8   5  13
  3   2   6   1   8 N
  3   3   5   1   6
  3   4   -   -   -
  3   5   -   -   -
  3   6   9   2  11
  4   1  13   5  18
  4   2   -   -   -
  4   3   6   2   8
  4   4   8   2  11 N
  4   5   8   4  13 N
  4   6  11   1  13 N
  5   1  18   5  23
  5   2   8   1   9
  5   3   8   5  13
  5   4   -   -   -
  5   5   -   -   -
  5   6   -   -   -
  6   1  23   4  27
  6   2   9   4  13
  6   3  13   2  15
  6   4  11   6  17
  6   5  13   1  14
  6   6  13   4  17
  7   1  27   4  31
  7   2  13   5  18
  7   3  15   4  19
  7   4  17   6  23
  7   5  14   2  16
  7   6  17   6  23
  8   1  31   5  36
  8   2  18   1  19
  8   3  19   4  23
  8   4  23   1  24
  8   5  16   5  21
  8   6  23   5  28 F
  9   1  36   3  40 N
  9   2  19   4  23
  9   3  23   2  26 N
  9   4  24   1  26 N
  9   5  21   4  26 N
 10   1   -   -   -
 10   2  23   5  28 F
 10   3   -   -   -
 10   4   -   -   -
 10   5   -   -   -
 11   1  40   4  44
 11   3  26   3  29 F
 11   4  26   4  31 N
 11   5  26   3  29 F
 12   1  44   3  48 N
 12   4   -   -   -
 13   1   -   -   -
 13   4  31   2  33
 14   1  48   4  52 *
 14   4  33   3  36
 15   4  36   1  37
 16   4  37   5  42
 17   4  42   2  44
 18   4  44   5  49
 19   4  49   4  53 *',
        ]);
    }

    private function createScenario10(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Dostihy - do cieľa nedobehne žiadny kôň.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=9',
            'stdin' => '64 29 15',
            'stdout' => '  1   1  -1   1   1
  1   2  -1   5   5
  1   3  -1   6   7 N
  1   4  -1   2   2
  1   5  -1   1   1
  1   6  -1   3   3 F
  1   7  -1   4   5 N
  1   8  -1   3   3 F
  1   9  -1   3   3 F
  2   1   1   4   5
  2   2   5   5  10
  2   3   -   -   -
  2   4   2   3   5
  2   5   1   4   5
  2   7   -   -   -
  3   1   5   3   9 N
  3   2  10   2  13 N
  3   3   7   5  13 N
  3   4   5   6  11 F
  3   5   5   6  11 F
  3   7   5   6  11 F
  4   1   -   -   -
  4   2   -   -   -
  4   3   -   -   -
  5   1   9   4  13
  5   2  13   6  19 F
  5   3  13   6  19 F
  6   1  13   3  17 N
  7   1   -   -   -
  8   1  17   3  20 F',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '36 14 88',
            'stdout' => '  1   1  -1   6   7 N
  1   2  -1   5   5 F
  2   1   -   -   -
  3   1   7   2  10 N
  4   1   -   -   -
  5   1  10   6  16 F',
        ]);

        $scenario->cases()->create([
            'stdin' => '70 20 1',
            'stdout' => '  1   1  -1   2   3 N
  1   2  -1   4   4
  1   3  -1   6   6
  1   4  -1   5   6 N
  2   1   -   -   -
  2   2   4   4   8
  2   3   6   4  10
  2   4   -   -   -
  3   1   3   1   4
  3   2   8   3  11
  3   3  10   2  12
  3   4   6   2   8
  4   1   4   3   8 N
  4   2  11   3  15 N
  4   3  12   2  15 N
  4   4   8   3  11
  5   1   -   -   -
  5   2   -   -   -
  5   3   -   -   -
  5   4  11   4  15
  6   1   8   2  10
  6   2  15   2  17
  6   3  15   4  19
  6   4  15   4  19
  7   1  10   2  12
  7   2  17   3  20
  7   3  19   2  21
  7   4  19   1  20
  8   1  12   6  18
  8   2  20   6  27 N
  8   3  21   3  24 F
  8   4  20   2  22
  9   1  18   1  19
  9   2   -   -   -
  9   4  22   2  24 F
 10   1  19   6  25 F
 10   2  27   6  33
 11   2  33   5  38
 12   2  38   5  44 N
 13   2   -   -   -
 14   2  44   5  49
 15   2  49   3  52
 16   2  52   1  53 F',
        ]);
    }

    private function createScenario11(Assignment $assignment)
    {
        $scenario = $assignment->testScenarios()->create([
            'title' => 'Výpis poradia koní v cieli.',
            'points' => 1,
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=3',
            'stdin' => '21 5 58',
            'stdout' => 'ORDER: 3',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=8',
            'stdin' => '43 8 400',
            'stdout' => 'ORDER: 7 1 5',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=12',
            'stdin' => '90 3 22',
            'stdout' => 'ORDER: 9 2 6 1 8 3 5 7 4 12 10 11',
        ]);
    }
}
