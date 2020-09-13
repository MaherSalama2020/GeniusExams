<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailResultNotification extends Notification
{
    use Queueable;
    protected $result;
    protected $examName;
    protected $examType;
    protected $passing_rate;
    protected $examDate;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($result, $examName, $examType, $passing_rate, $examDate)
    {
        //
        $this->result=$result;
        $this->examName=$examName;
        $this->examType=$examType;
        $this->passing_rate=$passing_rate;
        $this->examDate=$examDate;
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
        $result_string="";
        if($this->examType=='Exam'){
            $result_string = $this->result>=$this->passing_rate ? 'Congratulations' : 'Sorry';
        } else if($this->examType=='Practical Test'){
            $result_string = 'Practical test finished';
        }
        $rate=$this->examType=='Exam'? 'Passing rate: '.$this->passing_rate.'%.':'';
        return ( new MailMessage )
            ->greeting($result_string)
            ->subject( 'Genius Exams training result' )
            ->line( 'You finished '. $this->examName.' '. $this->examType .' at Genius Exams. '.$rate )
            ->line( "Your result: ".$this->result.'%' )
            ->line( "Date: ".$this->examDate )
            ->action( 'Keep Training', $link );
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
