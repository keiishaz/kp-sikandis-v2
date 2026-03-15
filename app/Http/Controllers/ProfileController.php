<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form edit profil
     */
    public function edit()
    {
        return view('admin.profile.edit');
    }

    /**
     * Memperbarui nama dan/atau password pengguna saat ini
     * (Hanya pembaruan data dasar, tidak dicatat dalam activity log)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // password opsional, namun jika diisi minimal 8 karakter dan harus dikonfirmasi
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validatedData['name'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
            $user->password_changed_at = now();
        }

        // Simpan langsung ke database secara quiet
        // agar tidak memicu model events/observer log aktivitas apapun
        $user->saveQuietly();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
