<?php

namespace App\Livewire\Admin\Master\PaymentMethod;

use App\Models\PaymentMethod\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterPaymentIndex extends Component
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

    public $account_number;

    public $bank;

    public $logo;

    public $order;

    public $is_active;

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
        $this->reset(['name', 'account_number', 'bank', 'logo', 'order', 'is_active', 'paymentMethodIdBeingEdited']);
        $this->showingPaymentMethodModal = true;
    }

    public function showEditModal($paymentMethodId)
    {
        $this->resetValidation();
        $this->paymentMethodIdBeingEdited = $paymentMethodId;
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);

        // Load Data
        $this->name = $paymentMethod->name;
        $this->account_number = $paymentMethod->account_number;
        $this->bank = $paymentMethod->bank;
        $this->logo = $paymentMethod->logo;
        $this->order = $paymentMethod->order;
        $this->is_active = $paymentMethod->is_active;
        $this->showingPaymentMethodModal = true;
    }

    public function savePaymentMethod()
    {
        $rules = [
            'name' => 'required|max:255',
            'account_number' => 'nullable',
            'bank' => 'nullable',
            'logo' => 'nullable',
            'order' => 'nullable',
            'is_active' => 'nullable',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->paymentMethodIdBeingEdited) {
                $paymentMethod = PaymentMethod::findOrFail($this->paymentMethodIdBeingEdited);

                $paymentMethod->update([
                    'name' => $this->name,
                    'account_number' => $this->account_number,
                    'bank' => $this->bank,
                    'logo' => $this->logo,
                    'order' => 1,
                    'is_active' => true,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Payment Method telah diperbarui.', icon: 'success');
            } else {
                PaymentMethod::create([
                    'name' => $this->name,
                    'account_number' => $this->account_number,
                    'bank' => $this->bank,
                    'logo' => $this->logo,
                    'order' => 1,
                    'is_active' => true,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Payment Method baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingPaymentMethodModal = false;
    }

    public function deletePaymentMethod($paymentMethodId)
    {
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);
        DB::transaction(function () use ($paymentMethod) {
            $paymentMethod->delete();
        });
        $this->showingPaymentMethodModal = false;
        $this->dispatch('swal', title: 'Dihapus!', text: 'PaymentMethod telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::orWhere('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage === 'all' ? PaymentMethod::count() : $this->perPage);

        return view('livewire.admin.master.payment-method.admin-master-payment-index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
