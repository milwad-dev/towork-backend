<?php

namespace Modules\Task\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Task\Models\Task;

class TaskReminderMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reminder: '.$this->task->title);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'Task::mail.reminder',
            with: [
                'url' => rtrim((string) config('app.frontend_url'), '/').'/tasks/'.$this->task->id,
            ],
        );
    }
}
