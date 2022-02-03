<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Note;


class GetAccess extends Notification
{
    use Queueable;
 private $note;

    /**
     * Create a new notification instance.
     *
     * @param Note $note
     */
    public function __construct(Note $note)
    {
       $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return [ 'mail',
            'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url('notes/'.$this->note->uuid);
        return (new MailMessage)
            ->greeting('Hello, '.$notifiable->name)
            ->line('Note #'.$this->note->id.'was open to you.')
            ->action('You can see the note at the link', $url)
            ->line('By!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {                         //сохранение в таблице базы данных в data столбце вашей notifications таблицы
        return [

            $this->note
        ];
    }
}
