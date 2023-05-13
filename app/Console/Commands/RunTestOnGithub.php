<?php

namespace App\Console\Commands;

use App\Actions\CreateTestingAssignment;
use App\Actions\FetchAndProcessSubmission;
use App\Actions\StoreSubmission;
use App\Dto\StoreSubmissionDto;
use App\Enums\Role;
use App\Enums\SubmissionSource;
use App\Models\User;
use Illuminate\Console\Command;

class RunTestOnGithub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run_test_on_github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run github test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        StoreSubmission $storeSubmission,
        FetchAndProcessSubmission $fetchAndProcessSubmission,
        CreateTestingAssignment $createTestingAssignment,
    ) {
        $assignment = $createTestingAssignment->execute();

        foreach (range(1, 10) as $index) {
            $repo = "prog2-{$index}";

            $user = User::updateOrCreate([
                'email' => "github{$index}@stuba.sk"
            ], [
                'first_name' => "Git{$index}",
                'last_name' => 'Hub',
                'full_name' => "Git{$index} Hub",
                'username' => "github{$index}",
                'role' => Role::STUDENT,
                'vcs_username' => 'erikgreasy',
                'github_access_token' => config('github.test_key'),
                'github_repo' => $repo,
                'password' => bcrypt('randompassword'),
            ]);

            $submission = $storeSubmission->execute(new StoreSubmissionDto(
                assignmentId: $assignment->id,
                userId: $user->id,
                ip: '127.0.0.1',
                source: SubmissionSource::VCS,
            ));

            $fetchAndProcessSubmission->onQueue()->execute($submission);
        }

        return Command::SUCCESS;
    }
}
