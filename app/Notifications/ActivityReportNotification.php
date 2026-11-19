<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ActivityReport;

class ActivityReportNotification extends Notification
{
    use Queueable;

    protected $report;
    protected $action;

    public function __construct(ActivityReport $report, string $action)
    {
        $this->report = $report;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reports/' . $this->report->id);

        $subjects = [
            'draft'    => 'Laporan Baru Menunggu Purpose',
            'purpose'  => 'Laporan Menunggu Approval Sekretaris',
            'approved' => 'Laporan Telah Disetujui',
            'rejected' => 'Laporan Ditolak dan Perlu Revisi',
            'updated'  => 'Laporan Direvisi',
        ];

        return (new MailMessage)
            ->subject($subjects[$this->action] ?? 'Notifikasi Laporan')
            ->view('email.activity-report', [
                'notifiable' => $notifiable,
                'report'     => $this->report,
                'action'     => $this->action,
                'url'        => $url
            ]);
    }
}
