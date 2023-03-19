<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
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

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'submission' => $this->submission,
            'assignment' => $this->submission->assignment,
        ];
    }
}
