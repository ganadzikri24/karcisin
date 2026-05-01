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

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,creator'],
        ];

        if (isset($data['role']) && $data['role'] === 'creator') {
            $rules['phone'] = ['required', 'string', 'max:15'];
            $rules['nik'] = ['required', 'string', 'min:16']; // Minimal 16 angka
            $rules['address'] = ['required', 'string', 'min:10'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            
            'phone' => $data['phone'] ?? null,
            'nik' => $data['nik'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
    }
}