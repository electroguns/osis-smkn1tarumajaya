<?php

namespace App\Notifications;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class BeritaMenungguPersetujuanNotification extends Notification
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
            ->subject('📝 Berita Baru Menunggu Persetujuan')
            ->view('email.berita_menunggu_persetujuan', [
                'berita' => $this->berita,
                'anggota_divisi_humas' => $this->anggota,
                'penerima' => $notifiable
            ]);
    }
}
