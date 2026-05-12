<?php

namespace App\Livewire\Admin;

use App\Models\PaymentMethod\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewPaymentMethodIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $bank;

    public $account_number;

    public $showingPaymentMethodModal = false;

    public $paymentMethodIdBeingEdited = null;

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
        $this->reset(['name', 'bank', 'account_number', 'paymentMethodIdBeingEdited']);
        $this->showingPaymentMethodModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->paymentMethodIdBeingEdited = $id;
        $model = PaymentMethod::findOrFail($id);

        $this->name = $model->name;
        $this->bank = $model->bank;
        $this->account_number = $model->account_number;

        $this->showingPaymentMethodModal = true;
    }

    public function savePaymentMethod()
    {
        $this->validate([
            'name' => 'required|max:255',
            'bank' => 'nullable',
            'account_number' => 'nullable',
        ]);

        DB::transaction(function () {
            if ($this->paymentMethodIdBeingEdited) {
                $model = PaymentMethod::findOrFail($this->paymentMethodIdBeingEdited);
                $model->update([
                    'name' => $this->name,
                    'bank' => $this->bank,
                    'account_number' => $this->account_number,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Metode Bayar telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                PaymentMethod::create([
                    'name' => $this->name,
                    'bank' => $this->bank,
                    'account_number' => $this->account_number,
                    'is_active' => true,
                    'order' => 1,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Metode Bayar baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingPaymentMethodModal = false;
    }

    public function deletePaymentMethod($id)
    {
        $model = PaymentMethod::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Metode Bayar telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = PaymentMethod::query();

        if ($this->search) {
            $query->where('name', 'ilike', '%'.$this->search.'%')
                ->orWhere('bank', 'ilike', '%'.$this->search.'%');
        }

        $paymentMethods = $query->latest()->paginate($this->perPage === 'all' ? PaymentMethod::count() : $this->perPage);

        return view('livewire.admin.new-payment-method-index', [
            'paymentMethods' => $paymentMethods,
        ])->layout('layouts.premium', ['title' => 'Master Metode Bayar']);
    }
}
