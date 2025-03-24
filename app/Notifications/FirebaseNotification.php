<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use FCM;

class FirebaseNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.s
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */

     //use Queueable;

    public function via($notifiable)
    {
        return ['fcm'];
    }
    // public function via($notifiable)
    // {
    //     return ['mail'];
    // }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
    public function toFcm($notifiable)
    {
        $notificationBuilder = new PayloadNotificationBuilder('New Notification');
        $notificationBuilder->setBody('testing!')
                            ->setSound('default');

        $notification = $notificationBuilder->build();
        $topic = new Topics();
        $topic->topic('news')->build();

        return FCM::sendToTopic($topic, null, $notification, null);
    }
}
