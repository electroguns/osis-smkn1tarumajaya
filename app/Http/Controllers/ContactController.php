<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('order')->get();
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'bendahara'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menambah kontak.');
        }

        $validated = $request->validate([
            'type' => 'required|in:email,telepon,lokasi',
            'label' => 'required|string|max:255',
            'value' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        $contact = Contact::create($validated);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $allowedRoles = ['ketua', 'wakil', 'sekretaris'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kontak.');
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'value' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($validated);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $allowedRoles = ['ketua', 'wakil', 'sekretaris'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kontak.');
        }

        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['success' => true]);
    }
}
