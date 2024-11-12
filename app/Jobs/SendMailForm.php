<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\MailFormStudent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailForm implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $link;

    public string $title;

    public string $facultyName;

    public string $interval;

    public string $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $email,
        string $title,
        string $facultyName,
        string $interval,
        string $link,
    ) {
        $this->link = $link;
        $this->title = $title;
        $this->facultyName = $facultyName;
        $this->interval = $interval;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)
                ->send(new MailFormStudent(
                    $this->title,
                    $this->facultyName,
                    $this->interval,
                    $this->link
                ));
        } catch (Exception $exception) {
            Log::error('Error job reply contact', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
        }

    }
}
