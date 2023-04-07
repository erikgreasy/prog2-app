<?php

namespace App\Notifications;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SubmissionProcessed extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
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

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'submission' => new SubmissionResource($this->submission),
            'assignment' => $this->submission->assignment,
        ];
    }
}
