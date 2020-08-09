<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionSendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $title;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $sender, string $title, string $msg)
    {
        $this->sender = $sender;
        $this->title = $title;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
                    ->from($this->sender)
                    ->view('mail.html.default_notification')
                    ->with([
                        'title' => $this->title,
                        'msg' => $this->msg
                    ]);
    }
}
