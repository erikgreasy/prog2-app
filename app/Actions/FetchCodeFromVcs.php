<?php

namespace App\Actions;

use App\Exceptions\WrongRepositoryStructureException;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Github\Exceptions\FailedCloneException;
use App\Github\Exceptions\BranchNotFoundException;
use App\Github\Exceptions\RepositoryNotFoundException;

class FetchCodeFromVcs
{
    public function __construct(
        private ResolveSubmissionFolder $resolveSubmissionFolder,
    )
    {
    }

    /**
     * @return string path to cloned file
     * @throws \Exception
     */
    public function execute(User $user, Submission $submission): string
    {
        $relativeDiskPath = $this->resolveSubmissionFolder->handle($submission);
        $absTargetPath = Storage::path($relativeDiskPath);

        $this
            ->cloneRepo($user, $absTargetPath, $submission->assignment->vcs_branch)
            ->validateFiles($relativeDiskPath)
            ->cleanUpFiles($relativeDiskPath);

        $filename = $this->getSubmitedFileName($relativeDiskPath);

        return Storage::path($filename);
    }

    private function cloneRepo(User $user, string $targetPath, string $branchName): self
    {
        if (!$user->hasVcsSetup()) {
            throw new \Exception('Trying to clone the repo for user that doesnt have VCS setup.');
        }

        $process = new Process([
            'git',
            'clone',
            '--branch',
            $branchName,
            "https://{$user->github_access_token}@github.com/{$user->vcs_username}/{$user->github_repo}.git",
            $targetPath,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            if (preg_match('/Remote branch ([a-zA-Z0-9]*) not found in upstream origin/', $process->getErrorOutput())) {
                throw new BranchNotFoundException($process->getErrorOutput());
            }

            if (preg_match('/Repository not found./', $process->getErrorOutput())) {
                throw new RepositoryNotFoundException($process->getErrorOutput());
            }

            throw new FailedCloneException($process->getErrorOutput());
        }

        return $this;
    }

    private function validateFiles(string $diskPath): self
    {
        $files = Storage::allFiles($diskPath);
        $wantedTypeFilesNo = 0;

        foreach ($files as $file) {
            if (strtolower(File::extension($file)) == 'c') {
                $wantedTypeFilesNo += 1;
            }
        }

        if ($wantedTypeFilesNo !== 1) {
            throw new WrongRepositoryStructureException('Multiple files of type .c found.');
        }

        return $this;
    }

    /**
     * Delete all files and directories other than our wanted file
     */
    private function cleanUpFiles(string $diskPath): void
    {
        $directories = Storage::allDirectories($diskPath);

        foreach ($directories as $directory) {
            Storage::deleteDirectory($directory);
        }

        $files = Storage::allFiles($diskPath);

        foreach ($files as $file) {
            if (strtolower(File::extension($file)) != 'c') {
                Storage::delete($file);
            }
        }
    }

    private function getSubmitedFileName(string $diskPath): string
    {
        $files = Storage::allFiles($diskPath);

        return $files[0];
    }
}
