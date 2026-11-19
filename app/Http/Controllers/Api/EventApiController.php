<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\EventReminderNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventApiController extends Controller
{
    /**
     * List all events with attendance count.
     */
    public function index()
    {
        $events = Event::withCount('attendances')->latest('event_date')->get();

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * Show single event details.
     */
    public function show($id)
    {
        $event = Event::withCount('attendances')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $event,
        ]);
    }

    /**
     * List attendances for a specific event (Rekap Kehadiran).
     */
    public function attendances($id)
    {
        $event = Event::findOrFail($id);
        $attendances = Attendance::where('event_id', $event->id)
            ->with('user:id,name,kelas,jabatan,role,foto')
            ->latest('scanned_at')
            ->get();

        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalTerlambat = $attendances->where('status', 'terlambat')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'event_title' => $event->title,
                'event_date' => $event->event_date,
                'total_scanned' => $attendances->count(),
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'list' => $attendances,
            ],
        ]);
    }

    /**
     * Petugas Scanner Barcode / QR Absensi.
     * Allowed: Ketua, Wakil, Sekretaris, Ketua Divisi Humas, Anggota Divisi Humas
     */
    public function scan(Request $request, $id)
    {
        $user = $request->user();
        $event = Event::findOrFail($id);

        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas', 'anggota_humas'];
        if (!in_array($user->role, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki otoritas sebagai petugas scanner presensi.'
            ], 403);
        }

        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $qrToken = trim($request->qr_token);
        $targetUser = User::where('qr_token', $qrToken)->first();

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode / Token QR tidak valid atau tidak dikenali!'
            ], 404);
        }

        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $targetUser->id)
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'success' => false,
                'message' => "Pengurus {$targetUser->name} sudah melakukan presensi pada kegiatan ini."
            ], 400);
        }

        $now = Carbon::now();
        $eventStart = Carbon::parse($event->event_date . ' ' . $event->start_time);
        $toleransiMenit = (int) ($event->batas_waktu_absen ?? 15);
        $cutoff = $eventStart->copy()->addMinutes($toleransiMenit);

        if ($now->greaterThan($cutoff)) {
            $status = 'terlambat';
            $diffMenit = (int) $eventStart->diffInMinutes($now);
            $notes = "Terlambat {$diffMenit} menit (batas toleransi {$toleransiMenit} mnt)";
        } else {
            $status = 'hadir';
            $notes = 'Hadir tepat waktu (Scan Barcode Petugas)';
        }

        $attendance = Attendance::create([
            'event_id' => $event->id,
            'user_id' => $targetUser->id,
            'scanned_at' => $now,
            'status' => $status,
            'notes' => $notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => $status === 'terlambat' ? 'Presensi Dicatat: Terlambat' : 'Presensi Berhasil: Hadir Tepat Waktu!',
            'data' => [
                'attendance_id' => $attendance->id,
                'status' => strtoupper($status),
                'user_name' => $targetUser->name,
                'user_kelas' => $targetUser->kelas,
                'user_jabatan' => $targetUser->jabatan,
                'scanned_at' => $now->format('H:i:s'),
                'notes' => $notes,
            ],
        ]);
    }

    /**
     * Submit self attendance (Presensi Mandiri).
     */
    public function attend(Request $request, $id)
    {
        $user = $request->user();
        $event = Event::findOrFail($id);

        $existing = Attendance::where('event_id', $event->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pada kegiatan ini sebelumnya.',
                'data' => $existing,
            ], 400);
        }

        $now = Carbon::now();
        $eventStart = Carbon::parse($event->event_date . ' ' . $event->start_time);
        $toleransiMenit = (int) ($event->batas_waktu_absen ?? 15);
        $cutoff = $eventStart->copy()->addMinutes($toleransiMenit);

        $status = $now->greaterThan($cutoff) ? 'terlambat' : 'hadir';
        $notes = $status === 'terlambat' ? 'Terlambat (Presensi Mandiri)' : 'Hadir tepat waktu (Presensi Mandiri)';

        $attendance = Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'scanned_at' => $now,
            'status' => $status,
            'notes' => $notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat: ' . strtoupper($status),
            'data' => $attendance,
        ], 201);
    }

    /**
     * Create a new event (Sekretaris only).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'sekretaris') {
            return response()->json(['success' => false, 'message' => 'Hanya Sekretaris yang dapat membuat event.'], 403);
        }

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'description'=> 'nullable|string',
            'batas_waktu_absen' => 'nullable|integer|min:0',
        ]);

        $validated['batas_waktu_absen'] = $validated['batas_waktu_absen'] ?? 15;
        $validated['reminder_sent'] = 0;

        $event = Event::create($validated);

        return response()->json(['success' => true, 'message' => 'Event berhasil dibuat.', 'data' => $event], 201);
    }

    /**
     * Delete an event (Sekretaris only).
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'sekretaris') {
            return response()->json(['success' => false, 'message' => 'Hanya Sekretaris yang dapat menghapus event.'], 403);
        }

        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['success' => true, 'message' => 'Event berhasil dihapus.']);
    }

    /**
     * Broadcast reminder email to all OSIS members (Sekretaris only).
     */
    public function broadcast(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'sekretaris') {
            return response()->json(['success' => false, 'message' => 'Hanya Sekretaris yang dapat melakukan broadcast.'], 403);
        }

        $event = Event::findOrFail($id);
        $users = User::all();

        foreach ($users as $u) {
            $u->notify(new EventReminderNotification($event));
        }

        $event->update(['reminder_sent' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Siaran email pengingat berhasil dikirimkan ke seluruh pengurus OSIS!'
        ]);
    }
}
