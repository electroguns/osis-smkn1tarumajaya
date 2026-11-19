<?php

namespace App\Notifications;

use App\Models\Berita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeritaApprovedNotification extends Notification
{
    use Queueable;

    protected $berita;

    public function __construct(Berita $berita)
    {
        $this->berita = $berita;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Berita Anda Telah Disetujui!')
            ->view('email.berita-approved', [
                'notifiable' => $notifiable,
                'berita'     => $this->berita
            ]);
    }
}
