<?php

namespace App\Livewire\Contingent;

use App\Models\Official;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class Officials extends Component
{
    use WithPagination;

    public $contingent;

    public $search = '';

    // Form fields
    public $officialId = null;

    public $name = '';

    public $role = '';

    public $phone = '';

    public $isEditing = false;

    public function mount()
    {
        $user = Auth::user();
        if (! $user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }
        $this->contingent = $user->contingent;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->isEditing = true;
    }

    public function openEdit($id)
    {
        $official = Official::findOrFail($id);
        $this->officialId = $official->id;
        $this->name = $official->name;
        $this->role = $official->role;
        $this->phone = $official->phone;
        $this->isEditing = true;
    }

    public function resetForm()
    {
        $this->officialId = null;
        $this->name = '';
        $this->role = '';
        $this->phone = '';
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'contingent_id' => $this->contingent->id,
            'name' => $this->name,
            'role' => $this->role,
            'phone' => $this->phone,
        ];

        if ($this->officialId) {
            $official = Official::findOrFail($this->officialId);
            $official->update($data);
            $msg = 'Data official berhasil diperbarui.';
        } else {
            Official::create($data);
            $msg = 'Official baru berhasil ditambahkan.';
        }

        $this->resetForm();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Berhasil', 'text' => $msg]);
    }

    public function delete($id)
    {
        $official = Official::findOrFail($id);
        $official->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Terhapus', 'text' => 'Data official telah dihapus.']);
    }

    public function render()
    {
        $officials = Official::where('contingent_id', $this->contingent->id)
            ->when($this->search, function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('role', 'ilike', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.contingent.officials', [
            'officials' => $officials,
        ]);
    }
}
