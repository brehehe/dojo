<?php

namespace App\Livewire\Admin\Master\Technique;

use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterTechniqueIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public $search = '';

    public $perPage = 5;

    // User Fields
    public $name;

    public $showingTechniqueModal = false;

    public $techniqueIdBeingEdited = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'techniqueIdBeingEdited']);
        $this->showingTechniqueModal = true;
    }

    public function showEditModal($techniqueId)
    {
        $this->resetValidation();
        $this->techniqueIdBeingEdited = $techniqueId;
        $technique = Technique::findOrFail($techniqueId);

        // Load User Data
        $this->name = $technique->name;
        $this->showingTechniqueModal = true;
    }

    public function saveTechnique()
    {
        $rules = [
            'name' => 'required|min:3',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->techniqueIdBeingEdited) {
                $technique = Technique::findOrFail($this->techniqueIdBeingEdited);

                $technique->update([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Teknik & Jurus telah diperbarui.', icon: 'success');
            } else {
                Technique::create([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Teknik & Jurus baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingTechniqueModal = false;
    }

    public function deleteTechnique($techniqueId)
    {
        $technique = Technique::findOrFail($techniqueId);
        DB::transaction(function () use ($technique) {
            $technique->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Teknik & Jurus telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $techniques = Technique::orWhere('name', 'ilike', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Technique::count() : $this->perPage);

        return view('livewire.admin.master.technique.admin-master-technique-index', [
            'techniques' => $techniques,
        ]);
    }
}
