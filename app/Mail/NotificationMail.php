<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     */
    public User $user;

    /**
     * Notification title.
     */
    public string $notificationTitle;

    /**
     * Notification message.
     */
    public string $notificationMessage;

    /**
     * Notification type (info, success, warning, error).
     */
    public string $type;

    /**
     * Additional metadata.
     */
    public array $metadata;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $user,
        string $title,
        string $message,
        string $type = 'info',
        array $metadata = []
    ) {
        $this->user = $user;
        $this->notificationTitle = $title;
        $this->notificationMessage = $message;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notificationTitle . ' — ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'userName' => $this->user->name,
                'title' => $this->notificationTitle,
                'message' => $this->notificationMessage,
                'type' => $this->type,
                'metadata' => $this->metadata,
                'appName' => config('app.name'),
                'appUrl' => config('app.url'),
                'year' => date('Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
