<?php

namespace App\Notifications;

use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVacancyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new job vacancy has been posted.')
                    ->line('Job: ' . $this->vacancy->job->job_name)
                    ->line('Start Date: ' . $this->vacancy->start_date)
                    ->line('End Date: ' . $this->vacancy->end_date)
                    ->line('Description: ' . $this->vacancy->description)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
