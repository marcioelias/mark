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
    public $replyTo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $sender, string $replyTo, string $title, string $msg)
    {
        $this->sender = $sender;
        $this->reply_to = $replyTo;
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
                    ->from(config('mail.from.address'), $this->sender)
                    ->replyTo($this->reply_to, $this->sender)
                    ->view('mail.html.default_notification')
                    ->with([
                        'title' => $this->title,
                        'msg' => $this->msg
                    ]);
    }
}
