<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubmissionResource;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use App\Notifications\SubmissionProcessed;
use App\Notifications\UnsuccessfulSubmission;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationsController extends Controller
{
    public function __invoke()
    {
        /** @var User */
        $user = auth()->user();

        $unreadUserNotifications = $user->unreadNotifications()->get()->map(function (DatabaseNotification $notification) {
            if ($notification->type === SubmissionProcessed::class) {
                $notification->data = array_merge($notification->data, [
                    'submission' => new SubmissionResource(Submission::find($notification->data['submission_id'])),
                    'assignment' => Assignment::find($notification->data['assignment_id']),
                ]);
            } else if ($notification->type === UnsuccessfulSubmission::class) {
                $notification->data = array_merge($notification->data, [
                    'submission' => new SubmissionResource(Submission::find($notification->data['submission_id'])),
                    'assignment' => Assignment::find($notification->data['assignment_id']),
                ]);
            }

            return $notification;
        });

        return $unreadUserNotifications;
    }
}
