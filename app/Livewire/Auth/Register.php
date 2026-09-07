<?php

namespace App\Livewire\Auth;

use App\Mail\ContingentAccountCreatedMail;
use App\Models\Contingent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.new-login')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $leader_phone = '';

    public string $contingent_name = '';

    public string $contingent_city = '';

    public string $address = '';

    public bool $registrationSuccess = false;

    public string $registeredEmail = '';

    public string $registeredContingent = '';

    public bool $emailSent = true;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'leader_phone' => ['required', 'string', 'min:8', 'max:25'],
            'contingent_name' => ['required', 'string', 'min:3', 'max:255'],
            'contingent_city' => ['required', 'string', 'min:2', 'max:255'],
            'address' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama Manager / Official wajib diisi.',
            'name.min' => 'Nama Manager minimal 3 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'leader_phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'contingent_name.required' => 'Nama Kontingen wajib diisi.',
            'contingent_name.min' => 'Nama Kontingen minimal 3 karakter.',
            'contingent_city.required' => 'Kabupaten / Kota wajib diisi.',
            'address.required' => 'Alamat kantor / dojo sekretariat wajib diisi.',
            'address.min' => 'Alamat minimal 5 karakter.',
        ];
    }

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {
        $this->validate();

        // Generate a clean, secure 10-character alphanumeric password
        $plainPassword = Str::password(10, letters: true, numbers: true, symbols: false);

        /** @var User $user */
        /** @var Contingent $contingent */
        [$user, $contingent] = DB::transaction(function () use ($plainPassword) {
            $user = User::create([
                'name' => trim($this->name),
                'email' => strtolower(trim($this->email)),
                'password' => Hash::make($plainPassword),
            ]);

            // Ensure role exists and assign 'Contingent'
            Role::firstOrCreate(['name' => 'Contingent', 'guard_name' => 'web']);
            $user->assignRole('Contingent');

            $contingent = Contingent::create([
                'user_id' => $user->id,
                'name' => trim($this->contingent_name),
                'kab_kota' => trim($this->contingent_city),
                'leader_name' => trim($this->name),
                'leader_phone' => trim($this->leader_phone),
                'email' => strtolower(trim($this->email)),
                'address' => trim($this->address),
            ]);

            return [$user, $contingent];
        });

        // Queue credentials email to background worker (Supervisor)
        try {
            Mail::to($user->email)->queue(new ContingentAccountCreatedMail($user, $contingent, $plainPassword));
            $this->emailSent = true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengantrekan email kredensial akun kontingen: '.$e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            $this->emailSent = false;
        }

        $this->registeredEmail = $user->email;
        $this->registeredContingent = $contingent->name;
        $this->registrationSuccess = true;
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
