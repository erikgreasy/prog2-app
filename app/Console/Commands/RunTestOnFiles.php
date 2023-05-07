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
            'stdout' => '2 37 11 4
  0010100100000000100100101001010101000
    1   1  -1   3   4 N
    1   2  -1   5   6 N
    2   1   -   -   -
    2   2   -   -   -
    3   1   4   4   9 N
    3   2   6   5  11
    4   1   -   -   -
    4   2  11   6  18 N
    5   1   9   3  12
    5   2   -   -   -
    6   1  12   5  18 N
    6   2  18   6  24
    7   1   -   -   -
    7   2  24   2  26
    8   1  18   5  24 N
    8   2  26   1  27
    9   1   -   -   -
    9   2  27   5  33 N
   10   1  24   2  26
   10   2   -   -   -
   11   1  26   3  29
   11   2  33   2  35
   12   1  29   6  35
   12   2  35   2  37
   13   1  35   5  40 *
   13   2  37   1  38 *
  ORDER: 1 2',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=10',
            'stdin' => '77 7 7',
            'stdout' => '10 77 7 7
  00000000100000000000100000000000001000000000100000101000000000000010000000000
    1   1  -1   5   5
    1   2  -1   2   2
    1   3  -1   2   2
    1   4  -1   5   5
    1   5  -1   3   3
    1   6  -1   2   2
    1   7  -1   3   3
    1   8  -1   4   4
    1   9  -1   3   3
    1  10  -1   4   4
    2   1   5   2   7
    2   2   2   3   5
    2   3   2   5   7
    2   4   5   2   7
    2   5   3   4   7
    2   6   2   6   8
    2   7   3   6  10 N
    2   8   4   6  10
    2   9   3   1   4
    2  10   4   4   8
    3   1   7   3  10
    3   2   5   1   6
    3   3   7   4  11
    3   4   7   2  10 N
    3   5   7   4  11
    3   6   8   2  10
    3   7   -   -   -
    3   8  10   2  12
    3   9   4   4   8
    3  10   8   2  10
    4   1  10   4  14
    4   2   6   3  10 N
    4   3  11   4  15
    4   4   -   -   -
    4   5  11   2  13
    4   6  10   1  11
    4   7  10   3  13
    4   8  12   3  15
    4   9   8   2  10
    4  10  10   2  12
    5   1  14   6  20
    5   2   -   -   -
    5   3  15   5  20
    5   4  10   2  12
    5   5  13   6  19
    5   6  11   5  16
    5   7  13   1  14
    5   8  15   3  18
    5   9  10   4  14
    5  10  12   4  16
    6   1  20   3  23
    6   2  10   2  12
    6   3  20   4  24
    6   4  12   3  15
    6   5  19   1  20
    6   6  16   2  18
    6   7  14   5  19
    6   8  18   2  20
    6   9  14   1  15
    6  10  16   1  17
    7   1  23   6  29
    7   2  12   5  17
    7   3  24   3  27
    7   4  15   1  16
    7   5  20   1  22 N
    7   6  18   2  20
    7   7  19   2  22 N
    7   8  20   6  26
    7   9  15   1  16
    7  10  17   2  19
    8   1  29   1  30
    8   2  17   2  19
    8   3  27   6  33
    8   4  16   1  17
    8   5   -   -   -
    8   6  20   1  22 N
    8   7   -   -   -
    8   8  26   1  27
    8   9  16   5  22 N
    8  10  19   5  24
    9   1  30   4  34
    9   2  19   4  23
    9   3  33   6  39
    9   4  17   2  19
    9   5  22   5  27
    9   6   -   -   -
    9   7  22   4  26
    9   8  27   4  31
    9   9   -   -   -
    9  10  24   1  25
   10   1  34   6  40
   10   2  23   4  27
   10   3  39   4  43
   10   4  19   2  22 N
   10   5  27   3  30
   10   6  22   4  26
   10   7  26   3  29
   10   8  31   5  36
   10   9  22   2  24
   10  10  25   5  30
   11   1  40   1  41
   11   2  27   1  28
   11   3  43   4  47
   11   4   -   -   -
   11   5  30   2  32
   11   6  26   5  31
   11   7  29   5  34
   11   8  36   3  39
   11   9  24   4  28
   11  10  30   4  34
   12   1  41   4  46 N
   12   2  28   6  34
   12   3  47   1  48
   12   4  22   3  25
   12   5  32   4  36
   12   6  31   6  37
   12   7  34   4  38
   12   8  39   4  43
   12   9  28   1  29
   12  10  34   4  38
   13   1   -   -   -
   13   2  34   3  37
   13   3  48   1  49
   13   4  25   1  26
   13   5  36   1  37
   13   6  37   3  40
   13   7  38   2  40
   13   8  43   3  46
   13   9  29   6  36 N
   13  10  38   4  42
   14   1  46   4  50
   14   2  37   5  42
   14   3  49   5  54
   14   4  26   4  30
   14   5  37   4  41
   14   6  40   1  41
   14   7  40   6  46
   14   8  46   2  48
   14   9   -   -   -
   14  10  42   1  43
   15   1  50   4  54
   15   2  42   6  48
   15   3  54   1  55
   15   4  30   5  36 N
   15   5  41   4  46 N
   15   6  41   6  47
   15   7  46   3  49
   15   8  48   5  54 N
   15   9  36   1  37
   15  10  43   3  46
   16   1  54   5  59
   16   2  48   5  54 N
   16   3  55   2  57
   16   4   -   -   -
   16   5   -   -   -
   16   6  47   6  54 N
   16   7  49   5  54
   16   8   -   -   -
   16   9  37   4  41
   16  10  46   3  49
   17   1  59   5  64
   17   2   -   -   -
   17   3  57   2  59
   17   4  36   2  38
   17   5  46   5  52 N
   17   6   -   -   -
   17   7  54   4  58
   17   8  54   2  56
   17   9  41   3  44
   17  10  49   6  55
   18   1  64   4  68
   18   2  54   2  56
   18   3  59   1  60
   18   4  38   5  43
   18   5   -   -   -
   18   6  54   5  59
   18   7  58   3  61
   18   8  56   5  61
   18   9  44   3  47
   18  10  55   3  58
   19   1  68   1  69
   19   2  56   2  58
   19   3  60   6  66
   19   4  43   5  48
   19   5  52   3  55
   19   6  59   2  61
   19   7  61   4  65
   19   8  61   3  64
   19   9  47   1  48
   19  10  58   6  64
   20   1  69   5  74
   20   2  58   4  62
   20   3  66   4  70
   20   4  48   2  50
   20   5  55   3  58
   20   6  61   1  62
   20   7  65   2  68 N
   20   8  64   1  65
   20   9  48   5  54 N
   20  10  64   6  70
   21   1  74   6  80 *
   21   2  62   4  66
   21   3  70   5  75
   21   4  50   5  55
   21   5  58   6  64
   21   6  62   5  68 N
   21   7   -   -   -
   21   8  65   2  68 N
   21   9   -   -   -
   21  10  70   1  71
   22   2  66   2  68
   22   3  75   3  78 *
   22   4  55   4  59
   22   5  64   3  68 N
   22   6   -   -   -
   22   7  68   3  71
   22   8   -   -   -
   22   9  54   2  56
   22  10  71   2  73
   23   2  68   5  73
   23   4  59   3  62
   23   5   -   -   -
   23   6  68   6  74
   23   7  71   6  77
   23   8  68   6  74
   23   9  56   2  58
   23  10  73   3  76
   24   2  73   4  77
   24   4  62   3  65
   24   5  68   5  73
   24   6  74   2  76
   24   7  77   4  81 *
   24   8  74   3  77
   24   9  58   4  62
   24  10  76   2  78 *
   25   2  77   6  83 *
   25   4  65   3  68
   25   5  73   1  74
   25   6  76   6  82 *
   25   8  77   5  82 *
   25   9  62   1  63
   26   4  68   6  74
   26   5  74   6  80 *
   26   9  63   2  65
   27   4  74   5  79 *
   27   9  65   3  68
   28   9  68   6  74
   29   9  74   5  79 *
  ORDER: 1 3 7 10 2 6 8 5 4 9',
        ]);

        $scenario->cases()->create([
            'stdin' => '25 4 98',
            'stdout' => '4 25 4 98
  0000000100010000000100010
    1   1  -1   5   5
    1   2  -1   2   2
    1   3  -1   3   3
    1   4  -1   5   5
    2   1   5   5  10
    2   2   2   2   4
    2   3   3   6   9
    2   4   5   3   9 N
    3   1  10   4  14
    3   2   4   2   6
    3   3   9   2  11
    3   4   -   -   -
    4   1  14   2  16
    4   2   6   4  10
    4   3  11   3  14
    4   4   9   2  11
    5   1  16   3  19
    5   2  10   3  13
    5   3  14   2  16
    5   4  11   4  15
    6   1  19   4  23
    6   2  13   5  18
    6   3  16   4  21 N
    6   4  15   4  19
    7   1  23   3  26 *
    7   2  18   1  19
    7   3   -   -   -
    7   4  19   1  21 N
    8   2  19   4  23
    8   3  21   3  25 N
    8   4   -   -   -
    9   2  23   4  27 *
    9   3   -   -   -
    9   4  21   5  26 *
   10   3  25   5  30 *
  ORDER: 1 2 4 3',
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
            'stdout' => '3 20 6 55
  01000100100010110000
    1   1  -1   5   5
    1   2  -1   5   5
    1   3  -1   4   4
    2   1   5   2   7
    2   2   5   4  10 N
    2   3   4   2   7 N
    3   1   7   2  10 N
    3   2   -   -   -
    3   3   -   -   -
    4   1   -   -   -
    4   2  10   1  11
    4   3   7   2  10 N
    5   1  10   2  12
    5   2  11   2  14 N
    5   3   -   -   -
    6   1  12   2  14
    6   2   -   -   -
    6   3  10   6  17 N
    7   1  14   1  15 F
    7   2  14   2  17 N
    7   3   -   -   -
    8   2   -   -   -
    8   3  17   3  20
    9   2  17   1  18
    9   3  20   6  26 *
   10   2  18   5  23 *
  ORDER: 3 2',
        ]);

        $scenario->cases()->create([
            'stdin' => '52 25 9',
            'stdout' => '4 52 25 9
  0011101001101011001011000010010001110110101001011010
    1   1  -1   4   4 F
    1   2  -1   1   1
    1   3  -1   2   2
    1   4  -1   5   6 N
    2   2   1   4   6 N
    2   3   2   2   4 F
    2   4   -   -   -
    3   2   -   -   -
    3   4   6   1   8 N
    4   2   6   2   8
    4   4   -   -   -
    5   2   8   5  14 N
    5   4   8   4  12
    6   2   -   -   -
    6   4  12   1  14 N
    7   2  14   5  20 N
    7   4   -   -   -
    8   2   -   -   -
    8   4  14   4  18
    9   2  20   2  23 N
    9   4  18   3  21 F
   10   2   -   -   -
   11   2  23   5  28
   12   2  28   1  29
   13   2  29   4  33
   14   2  33   6  40 N
   15   2   -   -   -
   16   2  40   5  45
   17   2  45   5  50
   18   2  50   4  54 *
  ORDER: 2',
        ]);

        $scenario->cases()->create([
            'stdin' => '85 31 965',
            'stdout' => '4 85 31 965
  0011000111000110000110000010000000100110110010010000101101110101000001000100101001100
    1   1  -1   6   6
    1   2  -1   2   2
    1   3  -1   5   5
    1   4  -1   5   5
    2   1   6   1   7
    2   2   2   4   6
    2   3   5   4   9 F
    2   4   5   2   7
    3   1   7   5  12
    3   2   6   2   8 F
    3   4   7   5  12
    4   1  12   1  13
    4   4  12   3  16 N
    5   1  13   2  16 N
    5   4   -   -   -
    6   1   -   -   -
    6   4  16   2  18
    7   1  16   5  22 N
    7   4  18   1  19
    8   1   -   -   -
    8   4  19   6  25
    9   1  22   3  25
    9   4  25   6  31
   10   1  25   2  28 N
   10   4  31   1  32
   11   1   -   -   -
   11   4  32   3  36 N
   12   1  28   4  32
   12   4   -   -   -
   13   1  32   4  36
   13   4  36   4  40
   14   1  36   4  40
   14   4  40   6  46
   15   1  40   4  44
   15   4  46   5  51
   16   1  44   1  46 N
   16   4  51   6  57
   17   1   -   -   -
   17   4  57   2  59 F
   18   1  46   3  49
   19   1  49   1  50
   20   1  50   3  54 N
   21   1   -   -   -
   22   1  54   5  59 F
  ORDER:',
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
            'stdout' => '2 14 2 65
  00000000001010
    1   1  -1   1   1
    1   2  -1   6   6
    2   1   1   5   6
    2   2   6   3   9
    3   1   6   4  10
    3   2   9   3  12
    4   1  10   6  16 *
    4   2  12   6  18 *
  ORDER: 1 2',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=3',
            'stdin' => '10 1 6',
            'stdout' => '3 10 1 6
  0001000000
    1   1  -1   1   1
    1   2  -1   3   3
    1   3  -1   5   5
    2   1   1   4   5
    2   2   3   6   9
    2   3   5   1   6
    3   1   5   3   8
    3   2   9   3  12 *
    3   3   6   2   8
    4   1   8   5  13 *
    4   3   8   4  12 *
  ORDER: 2 1 3',
        ]);

        $scenario->cases()->create([
            'stdin' => '11 1 85',
            'stdout' => '4 11 1 85
  00000000100
    1   1  -1   3   3
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
    6   1  10   3  13 *
  ORDER: 2 3 4 1',
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
            'stdout' => '5 32 8 96
  01001000000101101000000001010000
    1   1  -1   2   3 N
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
   13   5  30   3  33 *
  ORDER: 4 3 1 2 5',
        ]);

        $scenario->cases()->create([
            'stdin' => '65 3 11',
            'stdout' => '4 65 3 11
  00000000000000100000000000000000000010000000000000000000010000000
    1   1  -1   6   6
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
   22   4  61   5  66 *
  ORDER: 1 2 3 4',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=8',
            'stdin' => '14 3 3',
            'stdout' => '8 14 3 3
  00101000001000
    1   1  -1   6   6
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
    9   3  14   2  16 *
  ORDER: 1 8 2 5 7 4 6 3',
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
            'stdout' => '4 12 3 753
  001011000000
    1   1  -1   4   4
    1   2  -1   2   2
    1   3  -1   5   5 F
    1   4  -1   2   2
    2   1   4   6  10
    2   2   2   3   5 F
    2   4   2   3   5 F
    3   1  10   1  11
    4   1  11   3  14 *
  ORDER: 1',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '16 4 5',
            'stdout' => '2 16 4 5
  0110000010010000
    1   1  -1   2   2 F
    1   2  -1   5   5
    2   2   5   2   7
    3   2   7   6  13
    4   2  13   6  19 *
  ORDER: 2',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '15 5 102',
            'stdout' => '2 15 5 102
  011001100000100
    1   1  -1   4   4
    1   2  -1   4   4
    2   1   4   2   6 F
    2   2   4   4   8
    3   2   8   3  11
    4   2  11   1  12
    5   2  12   4  16 *
  ORDER: 2',
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
            'stdout' => '3 27 7 56
  000000001000000110110100100
    1   1  -1   3   3
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
   10   3  26   2  28 *
  ORDER: 1 3',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=6',
            'stdin' => '49 14 100',
            'stdout' => '6 49 14 100
  0001001001010000000100001001110100000010001010100
    1   1  -1   5   5
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
   19   4  49   4  53 *
  ORDER: 1 4',
        ]);

        $scenario->cases()->create([
            'stdin' => '99 30 50',
            'stdout' => '4 99 30 50
  000000010001000010000100011100001000100010110000001001000000101001000010011100111001001010101010000
    1   1  -1   6   6
    1   2  -1   1   1
    1   3  -1   3   3
    1   4  -1   4   4
    2   1   6   3   9
    2   2   1   6   7
    2   3   3   3   6
    2   4   4   2   6
    3   1   9   4  13
    3   2   7   1   9 N
    3   3   6   6  13 N
    3   4   6   6  13 N
    4   1  13   3  16
    4   2   -   -   -
    4   3   -   -   -
    4   4   -   -   -
    5   1  16   6  23 N
    5   2   9   4  13
    5   3  13   2  15
    5   4  13   4  18 N
    6   1   -   -   -
    6   2  13   3  16
    6   3  15   1  16
    6   4   -   -   -
    7   1  23   3  26 F
    7   2  16   4  20
    7   3  16   6  23 N
    7   4  18   6  24
    8   2  20   2  23 N
    8   3   -   -   -
    8   4  24   6  30
    9   2   -   -   -
    9   3  23   2  25
    9   4  30   4  34
   10   2  23   3  26 F
   10   3  25   6  31
   10   4  34   3  38 N
   11   3  31   2  34 N
   11   4   -   -   -
   12   3   -   -   -
   12   4  38   6  45 N
   13   3  34   2  36
   13   4   -   -   -
   14   3  36   2  38
   14   4  45   2  47
   15   3  38   6  45 N
   15   4  47   5  52
   16   3   -   -   -
   16   4  52   4  56
   17   3  45   6  52 N
   17   4  56   5  62 N
   18   3   -   -   -
   18   4   -   -   -
   19   3  52   3  55
   19   4  62   3  65
   20   3  55   2  57
   20   4  65   3  68
   21   3  57   1  58
   21   4  68   5  73
   22   3  58   4  62
   22   4  73   4  77
   23   3  62   1  64 N
   23   4  77   5  82
   24   3   -   -   -
   24   4  82   6  88
   25   3  64   5  69
   25   4  88   2  90
   26   3  69   2  72 N
   26   4  90   6  96
   27   3   -   -   -
   27   4  96   2  98
   28   3  72   2  74 F
   28   4  98   5 103 *
  ORDER: 4',
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
            'stdout' => '9 64 29 15
  0011010100110011001110000111011010101000001010010100111011000010
    1   1  -1   1   1
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
    8   1  17   3  20 F
  ORDER:',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=2',
            'stdin' => '36 14 88',
            'stdout' => '2 36 14 88
  000111001001100111000010000001001110
    1   1  -1   6   7 N
    1   2  -1   5   5 F
    2   1   -   -   -
    3   1   7   2  10 N
    4   1   -   -   -
    5   1  10   6  16 F
  ORDER:',
        ]);

        $scenario->cases()->create([
            'stdin' => '70 20 1',
            'stdout' => '4 70 20 1
  0100101010001100000000011100001100011001001001100000111000000000000000
    1   1  -1   2   3 N
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
   16   2  52   1  53 F
  ORDER:',
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
            'stdout' => '3 21 5 58
  000010010000110000100
    1   1  -1   3   3
    1   2  -1   5   6 N
    1   3  -1   6   6
    2   1   3   6   9
    2   2   -   -   -
    2   3   6   5  11
    3   1   9   3  12
    3   2   6   3   9
    3   3  11   1  12
    4   1  12   1  13 F
    4   2   9   4  13 F
    4   3  12   3  15
    5   3  15   3  18
    6   3  18   5  23 *
  ORDER: 3',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=8',
            'stdin' => '43 8 400',
            'stdout' => '8 43 8 400
  0001000000001000001000000010000100000011100
    1   1  -1   4   5 N
    1   2  -1   4   5 N
    1   3  -1   6   6
    1   4  -1   5   5
    1   5  -1   2   2
    1   6  -1   4   5 N
    1   7  -1   2   2
    1   8  -1   1   1
    2   1   -   -   -
    2   2   -   -   -
    2   3   6   2   8
    2   4   5   2   7
    2   5   2   3   5
    2   6   -   -   -
    2   7   2   3   5
    2   8   1   1   2
    3   1   5   6  11
    3   2   5   3   8
    3   3   8   3  11
    3   4   7   1   8
    3   5   5   1   6
    3   6   5   4   9
    3   7   5   5  10
    3   8   2   1   3
    4   1  11   1  12
    4   2   8   5  14 N
    4   3  11   1  12
    4   4   8   5  14 N
    4   5   6   3   9
    4   6   9   2  11
    4   7  10   4  14
    4   8   3   3   6
    5   1  12   1  14 N
    5   2   -   -   -
    5   3  12   5  17
    5   4   -   -   -
    5   5   9   3  12
    5   6  11   6  17
    5   7  14   4  18
    5   8   6   6  12
    6   1   -   -   -
    6   2  14   1  15
    6   3  17   4  21
    6   4  14   3  17
    6   5  12   6  18
    6   6  17   6  23
    6   7  18   5  23
    6   8  12   3  15
    7   1  14   4  18
    7   2  15   6  21
    7   3  21   4  25
    7   4  17   6  23
    7   5  18   3  21
    7   6  23   5  28
    7   7  23   4  28 N
    7   8  15   4  20 N
    8   1  18   5  23
    8   2  21   4  25
    8   3  25   2  28 N
    8   4  23   5  28
    8   5  21   2  23
    8   6  28   5  33
    8   7   -   -   -
    8   8   -   -   -
    9   1  23   6  29
    9   2  25   3  28
    9   3   -   -   -
    9   4  28   1  29
    9   5  23   6  29
    9   6  33   6  39 F
    9   7  28   3  31
    9   8  20   2  22
   10   1  29   3  33 N
   10   2  28   6  34
   10   3  28   6  34
   10   4  29   6  35
   10   5  29   5  34
   10   7  31   5  36
   10   8  22   5  28 N
   11   1   -   -   -
   11   2  34   1  35
   11   3  34   6  40 F
   11   4  35   3  38
   11   5  34   1  35
   11   7  36   6  42
   11   8   -   -   -
   12   1  33   4  37
   12   2  35   4  39 F
   12   4  38   1  39 F
   12   5  35   1  36
   12   7  42   4  46 *
   12   8  28   6  34
   13   1  37   4  42 N
   13   5  36   1  37
   13   8  34   1  35
   14   1   -   -   -
   14   5  37   4  42 N
   14   8  35   5  40 F
   15   1  42   1  43
   15   5   -   -   -
   16   1  43   3  46 *
   16   5  42   1  43
   17   5  43   4  47 *
  ORDER: 7 1 5',
        ]);

        $scenario->cases()->create([
            'gcc_macro_defs' => '-DK=12',
            'stdin' => '90 3 22',
            'stdout' => '12 90 3 22
  000000010000000000000000000000000000000000000000000000000000000001010000000000000000000000
    1   1  -1   5   5
    1   2  -1   6   6
    1   3  -1   5   5
    1   4  -1   6   6
    1   5  -1   1   1
    1   6  -1   1   1
    1   7  -1   3   3
    1   8  -1   5   5
    1   9  -1   4   4
    1  10  -1   1   1
    1  11  -1   3   3
    1  12  -1   6   6
    2   1   5   4   9
    2   2   6   3   9
    2   3   5   5  10
    2   4   6   3   9
    2   5   1   4   5
    2   6   1   4   5
    2   7   3   2   5
    2   8   5   1   6
    2   9   4   1   5
    2  10   1   5   6
    2  11   3   2   5
    2  12   6   5  11
    3   1   9   5  14
    3   2   9   6  15
    3   3  10   2  12
    3   4   9   3  12
    3   5   5   5  10
    3   6   5   4   9
    3   7   5   4   9
    3   8   6   5  11
    3   9   5   6  11
    3  10   6   4  10
    3  11   5   1   6
    3  12  11   4  15
    4   1  14   5  19
    4   2  15   6  21
    4   3  12   6  18
    4   4  12   2  14
    4   5  10   3  13
    4   6   9   1  10
    4   7   9   2  11
    4   8  11   2  13
    4   9  11   4  15
    4  10  10   2  12
    4  11   6   2   9 N
    4  12  15   5  20
    5   1  19   5  24
    5   2  21   6  27
    5   3  18   5  23
    5   4  14   4  18
    5   5  13   6  19
    5   6  10   3  13
    5   7  11   2  13
    5   8  13   5  18
    5   9  15   4  19
    5  10  12   4  16
    5  11   -   -   -
    5  12  20   6  26
    6   1  24   2  26
    6   2  27   2  29
    6   3  23   6  29
    6   4  18   1  19
    6   5  19   2  21
    6   6  13   6  19
    6   7  13   5  18
    6   8  18   4  22
    6   9  19   6  25
    6  10  16   3  19
    6  11   9   5  14
    6  12  26   3  29
    7   1  26   2  28
    7   2  29   1  30
    7   3  29   1  30
    7   4  19   6  25
    7   5  21   5  26
    7   6  19   5  24
    7   7  18   1  19
    7   8  22   3  25
    7   9  25   1  26
    7  10  19   2  21
    7  11  14   2  16
    7  12  29   2  31
    8   1  28   3  31
    8   2  30   6  36
    8   3  30   4  34
    8   4  25   6  31
    8   5  26   2  28
    8   6  24   3  27
    8   7  19   2  21
    8   8  25   6  31
    8   9  26   6  32
    8  10  21   3  24
    8  11  16   2  18
    8  12  31   1  32
    9   1  31   4  35
    9   2  36   4  40
    9   3  34   2  36
    9   4  31   3  34
    9   5  28   3  31
    9   6  27   6  33
    9   7  21   6  27
    9   8  31   3  34
    9   9  32   6  38
    9  10  24   1  25
    9  11  18   2  20
    9  12  32   3  35
   10   1  35   6  41
   10   2  40   5  45
   10   3  36   3  39
   10   4  34   6  40
   10   5  31   6  37
   10   6  33   3  36
   10   7  27   2  29
   10   8  34   1  35
   10   9  38   6  44
   10  10  25   1  26
   10  11  20   5  25
   10  12  35   5  40
   11   1  41   1  42
   11   2  45   2  47
   11   3  39   5  44
   11   4  40   4  44
   11   5  37   1  38
   11   6  36   6  42
   11   7  29   2  31
   11   8  35   1  36
   11   9  44   4  48
   11  10  26   3  29
   11  11  25   2  27
   11  12  40   3  43
   12   1  42   3  45
   12   2  47   5  52
   12   3  44   3  47
   12   4  44   2  46
   12   5  38   1  39
   12   6  42   3  45
   12   7  31   3  34
   12   8  36   4  40
   12   9  48   3  51
   12  10  29   4  33
   12  11  27   2  29
   12  12  43   1  44
   13   1  45   6  51
   13   2  52   5  57
   13   3  47   4  51
   13   4  46   1  47
   13   5  39   5  44
   13   6  45   2  47
   13   7  34   5  39
   13   8  40   3  43
   13   9  51   6  57
   13  10  33   2  35
   13  11  29   3  32
   13  12  44   1  45
   14   1  51   4  55
   14   2  57   2  59
   14   3  51   2  53
   14   4  47   5  52
   14   5  44   1  45
   14   6  47   5  52
   14   7  39   1  40
   14   8  43   4  47
   14   9  57   4  61
   14  10  35   6  41
   14  11  32   3  35
   14  12  45   2  47
   15   1  55   1  56
   15   2  59   6  65
   15   3  53   3  56
   15   4  52   4  56
   15   5  45   4  49
   15   6  52   3  55
   15   7  40   4  44
   15   8  47   5  52
   15   9  61   6  67
   15  10  41   3  44
   15  11  35   4  39
   15  12  47   2  49
   16   1  56   4  60
   16   2  65   5  70
   16   3  56   1  57
   16   4  56   3  59
   16   5  49   3  52
   16   6  55   4  59
   16   7  44   6  50
   16   8  52   5  57
   16   9  67   5  72
   16  10  44   4  48
   16  11  39   4  43
   16  12  49   2  51
   17   1  60   3  63
   17   2  70   5  75
   17   3  57   3  60
   17   4  59   2  61
   17   5  52   4  56
   17   6  59   1  60
   17   7  50   4  54
   17   8  57   2  59
   17   9  72   6  78
   17  10  48   1  49
   17  11  43   1  44
   17  12  51   1  52
   18   1  63   6  69
   18   2  75   4  79
   18   3  60   1  61
   18   4  61   2  63
   18   5  56   1  57
   18   6  60   4  64
   18   7  54   6  60
   18   8  59   5  64
   18   9  78   2  80
   18  10  49   4  53
   18  11  44   2  46
   18  12  52   2  54
   19   1  69   1  70
   19   2  79   1  80
   19   3  61   4  65
   19   4  63   4  67
   19   5  57   5  62
   19   6  64   6  70
   19   7  60   4  64
   19   8  64   1  65
   19   9  80   3  83
   19  10  53   4  57
   19  11  46   3  49
   19  12  54   3  57
   20   1  70   4  74
   20   2  80   2  82
   20   3  65   4  69
   20   4  67   2  69
   20   5  62   6  69 N
   20   6  70   4  74
   20   7  64   1  65
   20   8  65   4  69
   20   9  83   5  88
   20  10  57   4  61
   20  11  49   2  51
   20  12  57   2  59
   21   1  74   3  77
   21   2  82   2  84
   21   3  69   5  74
   21   4  69   1  70
   21   5   -   -   -
   21   6  74   6  80
   21   7  65   2  67
   21   8  69   6  75
   21   9  88   6  94 *
   21  10  61   1  62
   21  11  51   3  54
   21  12  59   2  61
   22   1  77   5  82
   22   2  84   6  90
   22   3  74   4  78
   22   4  70   4  74
   22   5  69   1  70
   22   6  80   3  83
   22   7  67   5  72
   22   8  75   6  81
   22  10  62   5  67
   22  11  54   1  55
   22  12  61   6  67
   23   1  82   1  83
   23   2  90   4  94 *
   23   3  78   1  79
   23   4  74   1  75
   23   5  70   6  76
   23   6  83   5  88
   23   7  72   5  77
   23   8  81   5  86
   23  10  67   1  69 N
   23  11  55   4  59
   23  12  67   6  73
   24   1  83   1  84
   24   3  79   2  81
   24   4  75   2  77
   24   5  76   6  82
   24   6  88   1  89
   24   7  77   1  78
   24   8  86   3  89
   24  10   -   -   -
   24  11  59   1  60
   24  12  73   4  77
   25   1  84   4  88
   25   3  81   3  84
   25   4  77   2  79
   25   5  82   5  87
   25   6  89   2  91 *
   25   7  78   6  84
   25   8  89   1  90
   25  10  69   2  71
   25  11  60   4  64
   25  12  77   6  83
   26   1  88   4  92 *
   26   3  84   5  89
   26   4  79   5  84
   26   5  87   2  89
   26   7  84   4  88
   26   8  90   2  92 *
   26  10  71   6  77
   26  11  64   1  65
   26  12  83   3  86
   27   3  89   2  91 *
   27   4  84   6  90
   27   5  89   6  95 *
   27   7  88   5  93 *
   27  10  77   6  83
   27  11  65   2  67
   27  12  86   1  87
   28   4  90   3  93 *
   28  10  83   2  85
   28  11  67   3  70
   28  12  87   3  90
   29  10  85   2  87
   29  11  70   4  74
   29  12  90   6  96 *
   30  10  87   2  89
   30  11  74   5  79
   31  10  89   1  90
   31  11  79   2  81
   32  10  90   1  91 *
   32  11  81   5  86
   33  11  86   3  89
   34  11  89   3  92 *
  ORDER: 9 2 6 1 8 3 5 7 4 12 10 11',
        ]);
    }
}
