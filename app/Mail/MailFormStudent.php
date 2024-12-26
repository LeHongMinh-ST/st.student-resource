<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailFormStudent extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;
    public string $title;
    public string $facultyName;
    public string $interval;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        string $title,
        string $facultyName,
        string $interval,
        string $link
    ) {
        $this->link = $link;
        $this->title = $title;
        $this->facultyName = $facultyName;
        $this->interval = $interval;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hệ Thống Quản Lý Sinh Viên Trực Tuyến',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.send_form',
            with: [
                'link' => $this->link,
                'title' => $this->title,
                'interval' => $this->interval,
                'facultyName' => $this->facultyName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
