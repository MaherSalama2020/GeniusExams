<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailOrderNotification extends Notification
{
    use Queueable;
    protected $certificates;
    protected $total;
    protected $subtotal;
    protected $orderDate;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($certificates, $total, $subtotal, $orderDate)
    {
        //
        $this->certificates=$certificates;
        $this->total=$total;
        $this->subtotal=$subtotal;
        $this->orderDate=$orderDate;
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
        $original_price = $this->total;
        $new_price = $this->subtotal!=0? $this->subtotal : $this->total;
        return ( new MailMessage )
            ->greeting('Hi!')
            ->subject( 'Your order on Genius Exams completed.' )
            ->line( 'Order Date:'. $this->orderDate )
            ->line( 'Original Price: $'. $original_price)
            ->line( 'Total: $'. $new_price)
            ->line( 'Order Details:' )
            ->line( $this->certificates)
            ->line( 'You can now start training on Genius Exams.' )
            ->action( 'Start Training', $link );
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
