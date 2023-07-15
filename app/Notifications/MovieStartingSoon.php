<?php

namespace App\Notifications;

use App\Models\Viewing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MovieStartingSoon extends Notification
{
    use Queueable;

    public $viewing;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Viewing $viewing)
    {
        $this->viewing = $viewing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Movie Starting Soon')
                    ->line('The movie "' . $this->viewing->movie->title . '" in group "' . $this->viewing->group->name . '" is starting in 10 minutes.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
