<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function scan(Event $event)
    {
        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas', 'anggota_humas'];

        if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki otoritas untuk menjadi petugas scanner.');
        }

        $today = Carbon::today()->toDateString();
        $eventDate = $event->event_date;
        $dateFormatted = Carbon::parse($event->event_date)->translatedFormat('d F Y');

        // Jika diakses sebelum hari acara
        if ($today < $eventDate) {
            return redirect()->route('event.index')->with('scan_alert', [
                'type' => 'info',
                'title' => 'Presensi Belum Dibuka',
                'message' => "Presensi barcode untuk kegiatan \"{$event->title}\" hanya bisa dimulai pada hari pelaksanaan acara ({$dateFormatted}). Silakan kembali saat hari acara tiba."
            ]);
        }

        // Jika diakses setelah hari acara
        if ($today > $eventDate) {
            return redirect()->route('event.index')->with('scan_alert', [
                'type' => 'warning',
                'title' => 'Presensi Sudah Berakhir',
                'message' => "Peringatan: Kegiatan \"{$event->title}\" telah selesai dilaksanakan pada {$dateFormatted}. Anda sudah tidak dapat melakukan absensi untuk kegiatan ini."
            ]);
        }

        $lastAttendance = Attendance::where('event_id', $event->id)
            ->with('user')
            ->latest('scanned_at')
            ->first();

        return view('attendance.scan', compact('event', 'lastAttendance'));
    }

    public function store(Request $request, Event $event)
    {
        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas', 'anggota_humas'];
        if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak. Anda bukan petugas absensi.'], 200);
        }

        $today = Carbon::today()->toDateString();
        $eventDate = $event->event_date;
        $dateFormatted = Carbon::parse($event->event_date)->translatedFormat('d F Y');

        // Validasi hari acara
        if ($today < $eventDate) {
            return response()->json([
                'status' => 'error',
                'message' => "Presensi barcode belum dibuka. Hanya bisa dilakukan pada hari pelaksanaan acara ({$dateFormatted})."
            ], 200);
        }

        if ($today > $eventDate) {
            return response()->json([
                'status' => 'error',
                'message' => "Peringatan: Kegiatan telah selesai dilaksanakan pada {$dateFormatted}. Anda sudah tidak dapat melakukan absensi."
            ], 200);
        }

        $request->validate([
            'qr_token' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($event->is_geofencing && $event->latitude && $event->longitude) {
            if (!$request->latitude || !$request->longitude) {
                return response()->json(['status' => 'error', 'message' => 'Gagal mendeteksi lokasi GPS Anda. Petugas wajib mengaktifkan izin GPS pada HP!'], 200);
            }

            $distance = $this->calculateDistance(
                $event->latitude,
                $event->longitude,
                $request->latitude,
                $request->longitude
            );

            $maxRadius = 100; // Radius batas 100 meter
            if ($distance > $maxRadius) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal! Perangkat Scanner berada di luar lingkungan sekolah. Presensi wajib dilakukan di area sekolah (Jarak saat ini: ' . round($distance) . ' meter).'
                ], 200);
            }
        }

        $qrToken = trim($request->qr_token);

        $user = User::where('qr_token', $qrToken)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak dikenali / tidak valid!'], 200);
        }

        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyAttended) {
            return response()->json(['status' => 'error', 'message' => $user->name . ' sudah melakukan presensi hari ini.'], 200);
        }

        $currentTime = Carbon::now();
        $eventStart = Carbon::parse($event->event_date . ' ' . $event->start_time);
        $toleransiMenit = (int) ($event->batas_waktu_absen ?? 15);
        $cutoffTepatWaktu = $eventStart->copy()->addMinutes($toleransiMenit);

        // Deteksi keterlambatan berbasis jam mulai (start_time) + batas_waktu_absen
        if ($currentTime->greaterThan($cutoffTepatWaktu)) {
            $status = 'terlambat';
            $diffMenit = (int) $eventStart->diffInMinutes($currentTime);
            $notes = "Terlambat {$diffMenit} menit (melewati batas toleransi {$toleransiMenit} menit dari jam mulai " . $eventStart->format('H:i') . ")";
        } else {
            $status = 'hadir';
            $notes = 'Hadir tepat waktu';
        }

        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'scanned_at' => $currentTime,
            'status' => $status,
            'notes' => $notes,
        ]);

        \App\Services\ActivityLogger::log(
            'STATUS',
            'Agenda & Event',
            "Petugas mencatat presensi " . strtoupper($status) . " untuk {$user->name} pada agenda {$event->title}" . ($status === 'terlambat' ? " ({$notes})" : ""),
            $event,
            ['user_id' => $user->id, 'status' => $status, 'notes' => $notes]
        );

        return response()->json([
            'status' => 'success',
            'message' => $status === 'terlambat' ? 'Presensi Dicatat (Terlambat)' : 'Presensi Berhasil (Tepat Waktu)!',
            'user_name' => $user->name,
            'status_absen' => strtoupper($status),
            'notes' => $notes,
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function report(Event $event, Request $request)
    {
        $search = $request->input('q');

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $attendances = Attendance::where('event_id', $event->id)->get()->keyBy('user_id');

        $totalPengurus = $users->total();
        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalTerlambat = $attendances->where('status', 'terlambat')->count();
        $totalAlpa = $totalPengurus - ($totalHadir + $totalTerlambat);

        return view('attendance.report', compact(
            'event',
            'users',
            'attendances',
            'totalHadir',
            'totalTerlambat',
            'totalAlpa',
            'totalPengurus'
        ));
    }
}