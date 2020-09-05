<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailContactUsNotification extends Notification
{
    use Queueable;
    protected $name;
    protected $email;
    protected $subject;
    protected $message;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($name, $email, $subject, $message)
    {
        //
        $this->name=$name;
        $this->email=$email;
        $this->subject=$subject;
        $this->message=$message;
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
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        return ( new MailMessage )
            ->greeting('Hi!')
            ->line("Genius Exams Contact request from: ".$this->name)
            ->line($this->email)
            ->subject( $this->subject )
            ->line($this->message);    
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
