<?php

namespace App\Notifications;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class BeritaDirevisiNotification extends Notification
{
    use Queueable, SerializesModels;

    public $berita;
    public $anggota;

    public function __construct(Berita $berita, User $anggota)
    {
        $this->berita = $berita;
        $this->anggota = $anggota;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✨ [Revisi Selesai] Berita: ' . $this->berita->title)
            ->view('email.berita_direvisi', [
                'berita' => $this->berita,
                'anggota_divisi_humas' => $this->anggota
            ]);
    }
}