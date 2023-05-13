<?php

namespace App\Console\Commands;

use App\Actions\CreateTestingAssignment;
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
    public function handle(CreateTestingAssignment $createTestingAssignment)
    {
        config(['database.connections.mysql.strict' => false]);

        $parsedData = $this->prepareStudentDataFromFiles();
        $assignment = $createTestingAssignment->execute();

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
                ->onQueue('tester')
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
}
