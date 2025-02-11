<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $sendData;

    public function __construct($sendData, $option)
    {
        $this->sendData = $sendData;
        $this->options = $option;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // subjectに環境を設定
        if (config('app.env') === 'local') {
            $subject = '【LOCAL】' . $this->options['subject'];
        } elseif (config('app.env') === 'development') {
            $subject = '【DEV】' . $this->options['subject'];
        } elseif (config('app.env') === 'edge') {
            $subject = '【edge】' . $this->options['subject'];
        } else {
            $subject = $this->options['subject'];
        }

        return $this->from($this->options['from'], $this->options['from_jp'])
            ->subject($subject)
            ->text($this->options['template']);
    }
}
