<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackNotification extends Notification
{
    use Queueable;

    protected $channel;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($channel = null, $message = null)
    {
        $this->channel = $channel;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $envName = config('app.env');

        // 各環境ごとに用意してあるスタンプの名前に変換
        $icon = 'local';
        if ($envName === 'production') {
            $icon = 'prd';
        } elseif ($envName === 'development') {
            $icon = 'dev';
        } elseif ($envName === 'edge') {
            $icon = 'edge_icon';
        }

        $message = (new SlackMessage)
            ->from($this->channel['username'], $icon)
            ->to($this->channel['channel'])
            ->content($this->message);

        return $message;
    }
}
