<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StrukturController extends Controller
{
    public function landingPage()
    {
        $users = User::all()->groupBy('role');
        return view('landing', compact('users'));
    }

    public function index(Request $request)
    {
        $search = $request->input('q');

        $user = Auth::user();

        $query = User::query();

        // Ketua & Wakil
        if (in_array($user->role, ['ketua', 'wakil'])) {
        }

        // Sekre & Bendahara
        elseif (in_array($user->role, ['sekretaris', 'bendahara'])) {

            $query->whereIn('role', [
                'ketua',
                'wakil',
                'sekretaris',
                'bendahara',
            ]);
        }

        // Ketua Divisi / Anggota Divisi
        elseif (
            Str::startsWith($user->role, 'ketua_divisi_') ||
            Str::startsWith($user->role, 'anggota_divisi_')
        ) {

            $divisi = explode('_', $user->role);
            $divisi = end($divisi);

            $query->where(function ($q) use ($divisi) {
                $q->where('role', 'like', "ketua_divisi_{$divisi}")
                    ->orWhere('role', 'like', "anggota_divisi_{$divisi}");
            });
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderByRaw("
            CASE
                WHEN role = 'ketua' THEN 1
                WHEN role = 'wakil' THEN 2
                WHEN role = 'sekretaris' THEN 3
                WHEN role = 'bendahara' THEN 4
                WHEN role LIKE 'ketua_divisi_%' THEN 5
                WHEN role LIKE 'anggota_divisi_%' THEN 6
                ELSE 999
            END
        ");

        $users = $query
            ->paginate(10)
            ->withQueryString();

        return view('struktur.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('struktur.show', compact('user'));
    }

    public function create()
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['ketua', 'wakil', 'sekretaris'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah pengurus.');
        }
        return view('struktur.create');
    }

    public function store(Request $request)
    {
        $auth = Auth::user();

        if (!in_array($auth->role, ['ketua', 'wakil', 'sekretaris'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'kelas' => 'required|string|max:100',
            'jabatan' => ['required', 'in:ketua,wakil,sekretaris,bendahara,ketua_divisi_humas,ketua_divisi_agama,ketua_divisi_olahraga,ketua_divisi_artmedia,anggota_divisi_humas,anggota_divisi_agama,anggota_divisi_olahraga,anggota_divisi_artmedia'],
            'foto' => 'nullable|image|max:5120',
            'password' => 'required|string|min:6',
        ]);

        $role = $request->jabatan;

        // Batasi Ketua Divisi agar hanya bisa mendaftarkan anggota di divisinya sendiri
        if (str_starts_with($auth->role, 'ketua_divisi_')) {
            $divisiAuth = str_replace('ketua_divisi_', '', $auth->role);
            if (!str_starts_with($role, 'anggota_divisi_') || !str_contains($role, $divisiAuth)) {
                abort(403, 'Anda hanya bisa menambahkan anggota untuk divisi Anda sendiri.');
            }
        }

        if (in_array($role, ['ketua', 'wakil', 'ketua_divisi_humas', 'ketua_divisi_agama', 'ketua_divisi_olahraga', 'ketua_divisi_artmedia'])) {
            $exists = User::where('role', $role)->exists();
            if ($exists) {
                return back()->withErrors([
                    'jabatan' => ucwords(str_replace('_', ' ', $role)) . ' sudah terisi!'
                ])->withInput();
            }
        }

        $filename = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/public/struktural');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'kelas' => $request->kelas,
            'foto' => $filename,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('struktur.index')->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $auth = Auth::user();
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy($auth)) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit data ini.');
        }

        return view('struktur.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $auth = Auth::user();
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy($auth)) {
            abort(403, 'Anda tidak memiliki hak untuk mengubah data ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'kelas' => 'required|string|max:100',
            'jabatan' => ['required', 'in:ketua,wakil,sekretaris,bendahara,ketua_divisi_humas,ketua_divisi_agama,ketua_divisi_olahraga,ketua_divisi_artmedia,anggota_divisi_humas,anggota_divisi_agama,anggota_divisi_olahraga,anggota_divisi_artmedia'],
            'foto' => 'nullable|image|max:5120',
        ]);

        $role = $request->jabatan;

        // Hanya Ketua, Wakil, dan Sekretaris yang bisa mengubah role/jabatan
        if (!in_array($auth->role, ['ketua', 'wakil', 'sekretaris']) && $user->role !== $role) {
            abort(403, 'Anda tidak diizinkan mengubah struktur jabatan.');
        }

        // Ketua Divisi tidak bisa mengubah role anggota divisi
        if (Str::startsWith($auth->role, 'ketua_divisi_') && $user->role !== $role) {
            abort(403, 'Anda tidak diizinkan mengubah struktur jabatan.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->kelas = $request->kelas;
        $user->role = $role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                $old = storage_path('app/public/struktural/' . $user->foto);
                if (file_exists($old)) unlink($old);
            }
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/struktural'), $filename);
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('struktur.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $auth = Auth::user();
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy($auth)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        if ($user->foto) {
            $old = storage_path('app/public/struktural/' . $user->foto);
            if (file_exists($old)) unlink($old);
        }

        $user->delete();

        return back()->with('success', 'Data pengurus berhasil dihapus.');
    }
}
