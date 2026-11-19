<?php

namespace App\Notifications;

use App\Models\Berita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeritaRejectedNotification extends Notification
{
    use Queueable;

    protected $berita;
    protected $alasan;

    public function __construct(Berita $berita, $alasan)
    {
        $this->berita = $berita;
        $this->alasan = $alasan;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Update Status Berita: Diperlukan Perbaikan')
            ->view('email.berita-rejected', [
                'notifiable' => $notifiable,
                'berita'     => $this->berita,
                'alasan'     => $this->alasan
            ]);
    }
}
