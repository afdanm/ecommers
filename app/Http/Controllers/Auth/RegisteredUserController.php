<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        // Validasi form pendaftaran
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15'], // Anda bisa ubah nullable menjadi required jika diperlukan
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        
        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username, // Tambahkan ini
            'email' => $request->email,
            'phone' => $request->phone,  // Pastikan ini sudah ada
            'password' => Hash::make($request->password),
        ]);
        
        // Trigger event Registered
        event(new Registered($user));

        // Login pengguna yang baru terdaftar
        Auth::login($user);

        // Redirect ke halaman home setelah login
        return redirect(route('home', absolute: false)); // Arahkan ke halaman home
    }
}
