<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeritaTerbaruNotification extends Notification
{
    use Queueable;

    protected $berita;

    // Kita passing data berita yang baru dibuat ke dalam email
    public function __construct($berita)
    {
        $this->berita = $berita;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
          ->subject('📰 Berita Terbaru dari OSIS SMKN 1 Tarumajaya!')
                ->view('email.berita-terbaru', ['berita' => $this->berita]);
    }
    
}