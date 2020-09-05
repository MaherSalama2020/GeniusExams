<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailVerifyNotification extends Notification
{
    use Queueable;
    protected $vtoken;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($vtoken)
    {
        //
        $this->vtoken=$vtoken;
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
        $link = url( "/api/user/verify/?verification_token=".$this->vtoken );
        return ( new MailMessage )
            ->greeting('Hi!')
            ->subject( 'Genius Email Verification Notification' )
            ->line( "You are receiving this email because we received a registration request." )
            ->action( 'Verify Email', $link )
            ->line( "This verify email link will expire in ".config('auth.passwords.users.expire')." minutes" )
            ->line( "If you did not request a verification, no further action is required." );    
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
