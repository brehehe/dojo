<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

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
    }

    public function login()
    {
        $this->validate();

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
        return view('livewire.auth.login')
            ->layout('layouts.auth');
    }
}
