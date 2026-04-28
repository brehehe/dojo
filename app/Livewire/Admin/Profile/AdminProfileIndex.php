<?php

namespace App\Livewire\Admin\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminProfileIndex extends Component
{
    public string $name = '';
    public string $email = '';
    
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // Role Specific Data
    public bool $is_referee = false;
    public array $referee_data = [];
    
    public bool $is_contingent = false;
    public array $contingent_data = [];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        // Check Referee
        $referee = \App\Models\Referee::where('user_id', $user->id)->first();
        if ($referee) {
            $this->is_referee = true;
            $this->referee_data = $referee->toArray();
        }

        // Check Contingent
        $contingent = \App\Models\Contingent::where('user_id', $user->id)->first();
        if ($contingent) {
            $this->is_contingent = true;
            $this->contingent_data = $contingent->toArray();
        }
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Update Referee Data
        if ($this->is_referee) {
            \App\Models\Referee::where('user_id', $user->id)->update([
                'nik' => $this->referee_data['nik'] ?? null,
                'phone' => $this->referee_data['phone'] ?? null,
                'province' => $this->referee_data['province'] ?? null,
                'city' => $this->referee_data['city'] ?? null,
                'certification_level' => $this->referee_data['certification_level'] ?? null,
            ]);
        }

        // Update Contingent Data
        if ($this->is_contingent) {
            \App\Models\Contingent::where('user_id', $user->id)->update([
                'leader_name' => $this->contingent_data['leader_name'] ?? null,
                'leader_phone' => $this->contingent_data['leader_phone'] ?? null,
                'kab_kota' => $this->contingent_data['kab_kota'] ?? null,
                'address' => $this->contingent_data['address'] ?? null,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Profil Berhasil Diperbarui',
            'text' => 'Perubahan nama dan email telah disimpan.'
        ]);
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Password Berhasil Diubah',
            'text' => 'Gunakan password baru Anda untuk login berikutnya.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.profile.admin-profile-index');
    }
}
