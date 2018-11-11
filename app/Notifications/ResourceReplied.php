<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResourceReplied extends Notification
{
    use Queueable;

    protected $resource;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
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
        $user = auth()->user();

        $resourceName = strtolower((new \ReflectionClass($this->resource))->getShortName());

        return [
            'message' => $user->username . ' replied your ' . $resourceName,
            'additional' => [
                'content' => $this->getResourceContent($resourceName),
                'sender_username' => $user->username,
            ],
        ];
    }

    public function getResourceContent($name)
    {
        if (! $name === 'track') {
            return $this->resource->body;
        }

        return $this->resource->title;
    }
}
