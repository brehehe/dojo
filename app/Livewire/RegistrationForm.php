<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Athlete;
use App\Models\Official;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\KyuLevel;

class RegistrationForm extends Component
{
    use WithFileUploads;

    // A. DATA KONTINGEN
    public string $contingent_city = '';
    public string $contingent_name = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $leader_email = '';
    public string $address = '';
    public $transfer_proof;

    // B. OFFICIAL 
    public array $officials = [
        ['name' => '', 'role' => '', 'phone' => '']
    ];

    // C. ATLET
    public array $athletes = [
        [
            'name' => '', 'gender' => 'Male', 'birth_date' => '', 'age_group' => 'Pemula',
            'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '', 'nik' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '', 'identity_document' => null
        ]
    ];
    
    // D. PERNYATAAN
    public string $sim_perkemi_confirm = 'Ya';

    public $kyuLevels;
    public bool $is_success = false;
    
    // Payment & Pricing
    public string $payment_method = 'BCA';
    public int $unique_code = 0;
    public string $referral_code = '';
    public int $contingent_fee = 2500000;
    public int $athlete_fee_pemula = 400000;
    public int $athlete_fee_lainnya = 500000;

    public function mount()
    {
        $this->unique_code = rand(100, 999);
        $this->kyuLevels = KyuLevel::orderBy('order', 'asc')->get();
    }

    protected function rules()
    {
        return [
            'contingent_city' => 'required',
            'contingent_name' => 'required|min:3',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            
            'officials.*.name' => 'required|min:3',
            'officials.*.role' => 'required',
            
            'athletes.*.name' => 'required|min:3',
            'athletes.*.gender' => 'required|in:Male,Female',
            'athletes.*.age_group' => 'required',
            'athletes.*.rank' => 'required',
            'athletes.*.dojo_origin' => 'required',
            'athletes.*.nik' => 'required',
            'athletes.*.bpjs_number' => 'required',
            'athletes.*.bpjs_status' => 'required|in:Aktif',
            'athletes.*.bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'athletes.*.identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function addOfficial()
    {
        $this->officials[] = ['name' => '', 'role' => '', 'phone' => ''];
    }

    public function removeOfficial($index)
    {
        if (count($this->officials) > 1) {
            unset($this->officials[$index]);
            $this->officials = array_values($this->officials);
        }
    }

    public function addAthlete()
    {
        $this->athletes[] = [
            'name' => '', 'gender' => 'Male', 'birth_date' => '', 'age_group' => 'Pemula',
            'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '', 'nik' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '', 'identity_document' => null
        ];
    }
    
    public function removeAthlete($index)
    {
        if (count($this->athletes) > 1) {
            unset($this->athletes[$index]);
            $this->athletes = array_values($this->athletes);
        }
    }
    
    public function getEventOptions($ageGroup, $gender)
    {
        return Category::where('age_group', $ageGroup)
            ->where('gender', $gender)
            ->pluck('name', 'id');
    }

    public function getAthletePemulaCount()
    {
        $count = 0;
        foreach ($this->athletes as $athlete) {
            if ($athlete['age_group'] === 'Pemula') {
                $count++;
            }
        }
        return $count;
    }

    public function getAthleteLainnyaCount()
    {
        $count = 0;
        foreach ($this->athletes as $athlete) {
            if ($athlete['age_group'] !== 'Pemula') {
                $count++;
            }
        }
        return $count;
    }

    public function getTotalAthleteFee()
    {
        return ($this->getAthletePemulaCount() * $this->athlete_fee_pemula) + ($this->getAthleteLainnyaCount() * $this->athlete_fee_lainnya);
    }

    public function getTotalProperty()
    {
        return $this->contingent_fee + $this->getTotalAthleteFee();
    }

    public function getFinalTotalProperty()
    {
        return $this->getTotalProperty() + $this->unique_code;
    }

    public function submit()
    {
        $this->validate();
        
        foreach ($this->athletes as $index => $athlete) {
            if ($athlete['bpjs_status'] !== 'Aktif') {
                $this->addError("athletes.{$index}.bpjs_status", 'Status BPJS harus Aktif.');
                return;
            }
            if (empty($athlete['event1']) && empty($athlete['event2']) && empty($athlete['event3'])) {
                $this->addError("athletes.{$index}.events", 'Minimal 1 nomor pertandingan harus dipilih.');
                return;
            }
            $events = array_filter([$athlete['event1'], $athlete['event2'], $athlete['event3']]);
            if (count($events) !== count(array_unique($events))) {
                $this->addError("athletes.{$index}.events", 'Nomor pertandingan tidak boleh ganda.');
                return;
            }
        }
        
        DB::transaction(function () {
            $transferPath = $this->transfer_proof ? $this->transfer_proof->store('transfer_proofs', 'public') : null;

            $contingent = Contingent::create([
                'name' => $this->contingent_name,
                'kab_kota' => $this->contingent_city,
                'leader_name' => $this->leader_name,
                'leader_phone' => $this->leader_phone,
                'email' => $this->leader_email,
                'address' => $this->address,
                'transfer_proof_path' => $transferPath,
                'sim_perkemi_confirm' => $this->sim_perkemi_confirm,
                'total_cost' => $this->getTotalProperty(),
                'status' => 'pending',
                'referral_code' => 'KEMPO-' . strtoupper(Str::random(5)),
                'payment_method' => $this->payment_method,
                'unique_code' => $this->unique_code,
                'final_amount' => $this->getFinalTotalProperty(),
            ]);
            
            $this->referral_code = $contingent->referral_code;

            foreach ($this->officials as $officialData) {
                $contingent->officials()->create($officialData);
            }

            foreach ($this->athletes as $athleteData) {
                $bpjsPath = $athleteData['bpjs_card'] ? $athleteData['bpjs_card']->store('bpjs_cards', 'public') : null;
                $identityPath = $athleteData['identity_document'] ? $athleteData['identity_document']->store('identity_docs', 'public') : null;

                $athlete = $contingent->athletes()->create([
                    'name' => $athleteData['name'],
                    'gender' => $athleteData['gender'],
                    'birth_date' => $athleteData['birth_date'],
                    'age_group' => $athleteData['age_group'],
                    'rank' => $athleteData['rank'],
                    'dojo_origin' => $athleteData['dojo_origin'],
                    'city' => $athleteData['city'],
                    'nik' => $athleteData['nik'],
                    'bpjs_number' => $athleteData['bpjs_number'],
                    'bpjs_status' => $athleteData['bpjs_status'],
                    'bpjs_card_path' => $bpjsPath,
                    'identity_document_path' => $identityPath,
                ]);
                
                $events = array_filter([$athleteData['event1'], $athleteData['event2'], $athleteData['event3']]);
                if (!empty($events)) {
                    $athlete->categories()->attach($events);
                }
            }
        });
        
        $this->is_success = true;
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}
