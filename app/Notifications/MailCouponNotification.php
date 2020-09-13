<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailCouponNotification extends Notification
{
    use Queueable;
    protected $code;
    protected $type;
    protected $value;
    protected $percent_off;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code, $type, $value, $percent_off)
    {
        //
        $this->code=$code;
        $this->type=$type;
        $this->value=$value;
        $this->percent_off=$percent_off;
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
        $link = url( "/" );
        $discount=$this->type=='fixed'?'$'.$this->value:$this->percent_off.'%';
        return ( new MailMessage )
            ->greeting('Hi!')
            ->subject( 'Genius Exams coupon' )
            ->line( 'Genius Exams give you a coupon, which you can apply throught shopping.' )
            ->line( "Your coupon code: ".$this->code )
            ->line( "Discount: ".$discount )
            ->action( 'Keep Shopping', $link )
            ->line( "This email link will expire in ".config('auth.passwords.users.expire')." minutes" )
            ->line( "If you did not want to apply this coupon, no further action is required." );
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
