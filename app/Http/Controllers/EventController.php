<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\EventReminderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query = Event::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $events = $query->paginate(10);
        return view('event.index', compact('events'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403, 'Anda tidak memiliki akses untuk membuat event.');
        }
        return view('event.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403, 'Anda tidak memiliki akses untuk membuat event.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'custom_category' => 'nullable|string|max:100',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'batas_waktu_absen' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_geofencing' => 'nullable',
        ]);

        $isGeofencing = $request->has('is_geofencing') ? 1 : 0;
        $latitude = $request->filled('latitude') ? $request->latitude : null;
        $longitude = $request->filled('longitude') ? $request->longitude : null;

        // Jika Kunci Lokasi aktif, koordinat WAJIB diisi
        if ($isGeofencing && (is_null($latitude) || is_null($longitude))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal! Jika Kunci Lokasi Sekolah diaktifkan, Anda wajib mengisi titik koordinat Latitude dan Longitude.');
        }

        $category = $request->category;
        if ($category === 'Lainnya' && $request->filled('custom_category')) {
            $category = trim($request->custom_category);
        }

        $startTime = preg_replace('/[^0-9:]/', '', (string) $request->start_time);
        $endTime = preg_replace('/[^0-9:]/', '', (string) $request->end_time);

        Event::create([
            'title' => $request->title,
            'category' => $category,
            'event_date' => $request->event_date,
            'start_time' => $startTime ?: $request->start_time,
            'end_time' => $endTime ?: $request->end_time,
            'batas_waktu_absen' => $request->filled('batas_waktu_absen') ? (int) $request->batas_waktu_absen : 15,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'is_geofencing' => $isGeofencing,
            'reminder_sent' => 0
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil dibuat!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus event.');
        }

        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus!');
    }

    public function broadcastEmail($id)
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan broadcast.');
        }

        $event = Event::findOrFail($id);

        $pengurusEmails = User::pluck('email')->toArray();

        if (empty($pengurusEmails)) {
            return redirect()->back()->with('error', 'Tidak ada data pengurus OSIS di database.');
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new EventReminderNotification($event));
        }

        \App\Services\ActivityLogger::log(
            'SIARAN',
            'Agenda & Event',
            "Mengirimkan siaran email notifikasi agenda: {$event->title} ke seluruh pengurus",
            $event
        );

        $event->disableLoggingOnce()->update(['reminder_sent' => true]);

        return redirect()->back()->with('success', 'Email broadcast berhasil dikirim ke semua pengurus!');
    }
}
