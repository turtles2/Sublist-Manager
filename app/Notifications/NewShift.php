<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Contacts;

use Carbon\Carbon;

class NewShift extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shift)
    {
        $this->shift = $shift;
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
        
        $start = $this->shift->starts;
        
        $end = $this->shift->ends;
        
        $posterid = $this->shift->poster;
        
        $code = $this->shift->code;
        
        $poster = Contacts::find($posterid);
        
        $starts = Carbon::parse($start);
        
        $ends = Carbon::parse($end);
        
        $starts = $starts->toDayDateTimeString();
        
        $ends = $ends->toDayDateTimeString();
        
        if($code == 0 or $code == 3){
            
            $type = "Lifeguarding Shift";
            
        }elseif($code == 1){
            
            $type = "Swim Instructor Shift";
            
        }else{
            
            $type = $code . 'Shift';
            
        }
        
        $message = $poster['fname'] .' '. $poster['lname'] . ' has posted a ' . $type . ' on Sublist ' . 'starting at ' . $starts . ' and ending at ' . $ends;
        
        return (new MailMessage)
                    ->line("$message")
                    ->action('Go to Sublist Now', 'https://www.sublistonline.com/log_in.php')
                    ->line('This Shift was sent automatically');
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
