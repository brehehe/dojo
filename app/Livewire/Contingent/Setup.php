<?php

namespace App\Livewire\Contingent;

use App\Models\Contingent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')] // Using auth layout for onboarding
class Setup extends Component
{
    public string $contingent_name = '';
    public string $contingent_city = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $address = '';

    public function mount()
    {
        // Redirect if profile already exists
        if (Auth::user()->contingent()->exists()) {
            return redirect()->route('dashboard');
        }
    }

    protected function rules(): array
    {
        return [
            'contingent_name' => 'required|min:3',
            'contingent_city' => 'required',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'address' => 'required',
        ];
    }

    public function saveProfile()
    {
        $this->validate();

        Contingent::create([
            'user_id' => Auth::id(),
            'name' => $this->contingent_name,
            'kab_kota' => $this->contingent_city,
            'leader_name' => $this->leader_name,
            'leader_phone' => $this->leader_phone,
            'email' => Auth::user()->email,
            'address' => $this->address,
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.contingent.setup');
    }
}
