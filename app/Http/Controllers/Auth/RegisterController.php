<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // VALIDASI DATA
    protected function validator(array $data)
    {
        // Aturan Dasar (Untuk Semua)
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,creator'],
        ];

        // Aturan Tambahan (Hanya Jika Role = Creator)
        if (isset($data['role']) && $data['role'] === 'creator') {
            $rules['phone'] = ['required', 'string', 'max:15'];
            $rules['nik'] = ['required', 'string', 'min:16']; // Minimal 16 angka
            $rules['address'] = ['required', 'string', 'min:10'];
        }

        return Validator::make($data, $rules);
    }

    // SIMPAN DATA KE DATABASE
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            
            // Simpan data tambahan (akan NULL jika user biasa)
            'phone' => $data['phone'] ?? null,
            'nik' => $data['nik'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
    }
}