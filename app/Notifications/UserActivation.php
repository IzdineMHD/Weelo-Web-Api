<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActivation extends Notification
{
    use Queueable;
    private $activation_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($activation_code)
    {
        $this->activation_code = Str::random(6);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/api/activation/'.$notifiable->activation_token);

        return (new MailMessage)
                    ->subject('Activation de compte')
                    ->line('Félicitation pour votre inscription mais vous devez activer votre compte pour continuer.')
                    ->line('Votre code d\'activation : ' . $this->activation_code)
                    ->line('Il expirera dans 60 min! Vous n\'avez plus qu\'à cliquer sur ce lien.')
                    ->action('Notification Action', url($url))
                    ->line('Merci encore de nous choisir et pour votre compréhension!');
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
