<?php

namespace App\Notifications;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnsuccessfulSubmission extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase()
    {
        return [
            'submission_id' => $this->submission->id,
            'assignment_id' => $this->submission->assignment_id,
        ];
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'submission' => new SubmissionResource($this->submission),
            'assignment' => $this->submission->assignment,
        ];
    }
}
