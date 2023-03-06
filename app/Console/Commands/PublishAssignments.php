<?php

namespace App\Console\Commands;

use App\Enums\AssignmentStatus;
use App\Models\Assignment;
use Illuminate\Console\Command;

class PublishAssignments extends Command
{
    protected $signature = 'prog:publish-assignments';

    protected $description = 'Process assignments that are scheduled to be published';

    public function handle()
    {
        $assignmentsToBePublished = Assignment::whereNot('status', AssignmentStatus::PUBLISH)->whereNotNull('published_at')->get();

        $assignmentsToBePublished->each(function (Assignment $assignment) {
            if (now() > $assignment->published_at) {
                $assignment->update(['status' => AssignmentStatus::PUBLISH]);
            }
        });

        return Command::SUCCESS;
    }
}
