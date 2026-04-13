<?php

namespace App\Livewire;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\KyuLevel;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Official;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegistrationForm extends Component
{
    use WithFileUploads;

    public string $contingent_id = '';
    public string $contingent_city = '';
    public string $contingent_name = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $leader_email = '';
    public string $address = '';
    public $transfer_proof;
    public bool $is_authenticated = false;

    // B. OFFICIAL
    public array $officials = [
        ['name' => '', 'role' => '', 'phone' => ''],
    ];

    // C. ATLET
    public array $athletes = [
        [
            'athlete_id' => '', 'nik' => '', 'name' => '', 'gender' => 'Male', 'birth_date' => '', 
            'current_weight' => '', 'weight_group_id' => '',
            'age_group' => '', 'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '',
            'identity_document' => null,
            'is_master_found' => false, 'show_fields' => false
        ],
    ];

    public array $matchTechniques = [];

    public $weightGroups;
    public $masterAthletes;
    public $ageGroups;

    // D. PERNYATAAN
    public string $sim_perkemi_confirm = 'Ya';

    public $kyuLevels;
    public $techniques;

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
        $this->weightGroups = WeightGroup::orderBy('order', 'asc')->get();
        $this->ageGroups = AgeGroup::orderBy('order', 'asc')->get();
        $this->techniques = \App\Models\Technique\Technique::orderBy('order', 'asc')->get();

        // Set default age_group for first athlete
        if ($this->ageGroups->isNotEmpty()) {
            $this->athletes[0]['age_group'] = $this->ageGroups->first()->id;
        }
        
        // Initial state: No master athletes for guests (Privacy)
        $this->masterAthletes = collect();

        if (auth()->check()) {
            $contingent = auth()->user()->contingent;
            if ($contingent) {
                $this->loadContingentData($contingent);
                // Load ONLY athletes from this contingent
                $this->masterAthletes = $contingent->athletes()
                    ->orderBy('name', 'asc')
                    ->get(['athletes.id', 'athletes.name', 'athletes.nik']);
            }
        }
    }

    public function loadContingentData($contingent)
    {
        if ($contingent) {
            $this->is_authenticated = true;
            $this->contingent_id = $contingent->id;
            $this->contingent_name = $contingent->name;
            $this->contingent_city = $contingent->kab_kota;
            $this->leader_name = $contingent->leader_name;
            $this->leader_phone = $contingent->leader_phone;
            $this->leader_email = $contingent->email;
            $this->address = $contingent->address;
        }
    }

    protected function rules()
    {
        return [
            'contingent_city' => 'required',
            'contingent_name' => 'required|min:3',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'leader_email' => 'required|email',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'officials.*.name' => 'required|min:3',
            'officials.*.role' => 'required',

            'athletes.*.name' => 'required|min:3',
            'athletes.*.gender' => 'required|in:Male,Female',
            'athletes.*.age_group' => 'required',
            'athletes.*.rank' => 'required',
            'athletes.*.dojo_origin' => 'required',
            'athletes.*.nik' => 'required|numeric|digits:16',
            'athletes.*.bpjs_number' => 'required|numeric',
            'athletes.*.bpjs_status' => 'required|in:Aktif',
            'athletes.*.current_weight' => 'required|numeric',
            'athletes.*.weight_group_id' => 'required',
            'athletes.*.bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'athletes.*.identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    protected function validationAttributes()
    {
        $attributes = [
            'contingent_city' => 'Kabupaten/Kota',
            'contingent_name' => 'Nama Kontingen',
            'leader_name' => 'Nama Manager',
            'leader_phone' => 'Nomor HP Manager',
            'leader_email' => 'Email Official',
            'transfer_proof' => 'Bukti Transfer',
        ];

        foreach ($this->officials as $index => $official) {
            $prefix = 'Official #' . ($index + 1);
            $attributes["officials.{$index}.name"] = "{$prefix} Nama";
            $attributes["officials.{$index}.role"] = "{$prefix} Jabatan";
        }

        foreach ($this->athletes as $index => $athlete) {
            $prefix = 'Atlet #' . ($index + 1);
            $attributes["athletes.{$index}.name"] = "{$prefix} Nama Lengkap";
            $attributes["athletes.{$index}.nik"] = "{$prefix} NIK";
            $attributes["athletes.{$index}.age_group"] = "{$prefix} Kelompok Usia";
            $attributes["athletes.{$index}.rank"] = "{$prefix} Tingkatan (Rank)";
            $attributes["athletes.{$index}.dojo_origin"] = "{$prefix} Asal Dojo";
            $attributes["athletes.{$index}.bpjs_number"] = "{$prefix} Nomor BPJS";
            $attributes["athletes.{$index}.current_weight"] = "{$prefix} Berat Badan";
            $attributes["athletes.{$index}.weight_group_id"] = "{$prefix} Kelompok Berat";
        }

        return $attributes;
    }

    public function updatedAthletes($value, $key)
    {
        // 1. Check if athlete_id was updated
        if (str_contains($key, 'athlete_id')) {
            $parts = explode('.', $key);
            $index = $parts[0];
            $athleteId = $value;

            if ($athleteId === 'new') {
                $this->athletes[$index]['is_master_found'] = false;
                $this->athletes[$index]['show_fields'] = true;
                $this->athletes[$index]['nik'] = '';
                $this->athletes[$index]['name'] = '';
                return;
            }

            if (!empty($athleteId)) {
                $athlete = Athlete::find($athleteId);
                if ($athlete) {
                    $this->athletes[$index]['nik'] = $athlete->nik;
                    $this->athletes[$index]['name'] = $athlete->name;
                    $this->athletes[$index]['gender'] = $athlete->gender;
                    $this->athletes[$index]['birth_date'] = $athlete->birth_date;
                    $this->athletes[$index]['bpjs_number'] = $athlete->bpjs_number;
                    $this->athletes[$index]['bpjs_status'] = $athlete->bpjs_status;
                    $this->athletes[$index]['is_master_found'] = true;
                    $this->athletes[$index]['show_fields'] = true;
                }
            }
        }

        // 2. Check if an event category was updated
        if (str_contains($key, 'event1') || str_contains($key, 'event2') || str_contains($key, 'event3')) {
            if ($value && !isset($this->matchTechniques[$value])) {
                $this->matchTechniques[$value] = [];
            }
        }
    }

    public function searchAthlete($index)
    {
        $nik = $this->athletes[$index]['nik'];
        
        if (strlen($nik) < 16) {
            $this->addError("athletes.{$index}.nik", 'NIK harus 16 digit.');
            return;
        }

        $athlete = Athlete::where('nik', $nik)->first();

        if ($athlete) {
            $this->athletes[$index]['athlete_id'] = $athlete->id;
            $this->athletes[$index]['name'] = $athlete->name;
            $this->athletes[$index]['gender'] = $athlete->gender;
            $this->athletes[$index]['birth_date'] = $athlete->birth_date;
            $this->athletes[$index]['bpjs_number'] = $athlete->bpjs_number;
            $this->athletes[$index]['bpjs_status'] = $athlete->bpjs_status;
            $this->athletes[$index]['is_master_found'] = true;
            $this->athletes[$index]['show_fields'] = true;
            
            $this->dispatch('swal', title: 'Data Ditemukan!', text: "Data master untuk {$athlete->name} telah dimuat.", icon: 'success');
        } else {
            $this->athletes[$index]['athlete_id'] = 'new';
            $this->athletes[$index]['is_master_found'] = false;
            $this->athletes[$index]['show_fields'] = true;
            
            $this->dispatch('swal', title: 'Data Baru', text: "NIK tidak terdaftar. Silakan lengkapi data atlet baru.", icon: 'info');
        }
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
            'athlete_id' => '', 'nik' => '', 'name' => '', 'gender' => 'Male', 'birth_date' => '', 
            'current_weight' => '', 'weight_group_id' => '',
            'age_group' => '', 'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '',
            'identity_document' => null,
            'is_master_found' => false, 'show_fields' => false
        ];
    }

    public function removeAthlete($index)
    {
        if (count($this->athletes) > 1) {
            unset($this->athletes[$index]);
            $this->athletes = array_values($this->athletes);
        }
    }

    public function getEventOptions($ageGroupId, $gender, $currentAthleteIndex = null, $currentField = null)
    {
        if (empty($ageGroupId)) return [];

        return MatchNumber::where('age_group_id', $ageGroupId)
            ->where(function($query) use ($gender) {
                $query->where('gender', $gender)
                      ->orWhere('gender', 'Mix');
            })
            ->withCount(['athletes' => function($query) {
                if ($this->contingent_id) {
                    $query->whereExists(function ($q) {
                        $q->select(DB::raw(1))
                          ->from('registrations')
                          ->whereColumn('registrations.id', 'athlete_match_number.registration_id')
                          ->where('registrations.contingent_id', $this->contingent_id);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }])
            ->get()
            ->filter(function($matchNumber) use ($currentAthleteIndex, $currentField) {
                // 1. Get DB Count (Now scoped to THIS contingent)
                $dbCount = $matchNumber->athletes_count;

                // 2. Get Current Form Count (from other athletes OR other fields of same athlete)
                $formCount = 0;
                foreach ($this->athletes as $idx => $ath) {
                    foreach (['event1', 'event2', 'event3'] as $fld) {
                        // Skip if it's the exact same field we are currently rendering for
                        if ($currentAthleteIndex !== null && $idx == $currentAthleteIndex && $fld == $currentField) {
                            continue;
                        }
                        
                        if ($ath[$fld] == $matchNumber->id) {
                            $formCount++;
                        }
                    }
                }

                $totalOccupied = $dbCount + $formCount;

                // 3. Logic: If max_athletes > 0, check if we have space (per contingent)
                return $matchNumber->max_athletes == 0 || $totalOccupied < $matchNumber->max_athletes;
            })
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
        $total = 0;
        foreach ($this->athletes as $athleteData) {
            $ageGroup = $this->ageGroups->firstWhere('id', $athleteData['age_group']);
            if ($ageGroup) {
                $total += $ageGroup->price;
            }
        }
        return $total;
    }

    public function getTotalProperty()
    {
        return $this->contingent_fee + $this->getTotalAthleteFee();
    }

    public function getFinalTotalProperty()
    {
        return $this->getTotalProperty() + (int) $this->unique_code;
    }

    public function getMatchSummaryProperty()
    {
        $summary = [];
        $matchIds = [];
        
        // Collect unique match IDs
        foreach ($this->athletes as $athlete) {
            foreach (['event1', 'event2', 'event3'] as $field) {
                if ($athlete[$field]) $matchIds[] = $athlete[$field];
            }
        }

        if (empty($matchIds)) return [];

        $matches = MatchNumber::whereIn('id', array_unique($matchIds))->with('ageGroup')->get()->keyBy('id');
        $allTechniques = \App\Models\Technique\Technique::pluck('name', 'id')->toArray();

        // 1. Setup Match Grouping
        foreach ($matchIds as $mId) {
            if (isset($matches[$mId]) && !isset($summary[$mId])) {
                $match = $matches[$mId];
                
                // Resolve tech names for THIS match from global state
                $selectedTechs = $this->matchTechniques[$mId] ?? [];
                if (!is_array($selectedTechs)) $selectedTechs = [];
                
                $techNames = [];
                foreach ($selectedTechs as $tid) {
                    if (isset($allTechniques[$tid])) {
                        $techNames[] = $allTechniques[$tid];
                    }
                }

                $summary[$mId] = [
                    'name' => $match->name,
                    'age_group' => $match->ageGroup?->name ?? 'N/A',
                    'techniques' => $techNames, // Global for this match
                    'athletes' => []
                ];
            }
        }

        // 2. Add Athletes to Matches
        foreach ($this->athletes as $athlete) {
            if (empty($athlete['name'])) continue;

            foreach (['event1', 'event2', 'event3'] as $field) {
                $mId = $athlete[$field];
                if ($mId && isset($summary[$mId])) {
                    $summary[$mId]['athletes'][] = $athlete['name'];
                }
            }
        }

        return $summary;
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
            $contingentId = $this->contingent_id;

            if (!$this->is_authenticated) {
                // 1. Create Login User (With Random Password if not already registered)
                $tempPassword = Str::random(12);
                $user = User::create([
                    'name' => $this->contingent_name,
                    'email' => $this->leader_email,
                    'password' => Hash::make($tempPassword),
                ]);

                // 2. Assign Contingent Role
                $user->assignRole('Contingent');

                // 3. Create Contingent Master Data
                $contingent = Contingent::create([
                    'user_id' => $user->id,
                    'name' => $this->contingent_name,
                    'kab_kota' => $this->contingent_city,
                    'leader_name' => $this->leader_name,
                    'leader_phone' => $this->leader_phone,
                    'email' => $this->leader_email,
                    'address' => $this->address,
                ]);
                
                $contingentId = $contingent->id;
            } else {
                $contingent = Contingent::find($contingentId);
            }

            // 4. Create Registration (Formulir) Transactional Data
            $transferPath = $this->transfer_proof ? $this->transfer_proof->store('transfer_proofs', 'public') : null;

            $registration = Registration::create([
                'contingent_id' => $contingentId,
                'total_cost' => $this->getTotalProperty(),
                'final_amount' => $this->getFinalTotalProperty(),
                'unique_code' => $this->unique_code,
                'payment_method' => $this->payment_method,
                'referral_code' => 'KEMPO-'.strtoupper(Str::random(5)),
                'status' => 'pending',
                'transfer_proof_path' => $transferPath,
                'sim_perkemi_confirm' => $this->sim_perkemi_confirm,
            ]);

            $this->referral_code = $registration->referral_code;

            // 5. Link Officials to Registration (Find or Create Master)
            foreach ($this->officials as $officialData) {
                $official = Official::updateOrCreate(
                    ['phone' => $officialData['phone'], 'name' => $officialData['name']],
                    $officialData
                );

                $registration->officials()->attach($official->id, [
                    'role' => $officialData['role'] ?? 'Official',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 6. Link Athletes to Registration (Find or Create Master)
            foreach ($this->athletes as $athleteData) {
                $bpjsPath = $athleteData['bpjs_card'] ? $athleteData['bpjs_card']->store('bpjs_cards', 'public') : null;
                $identityPath = $athleteData['identity_document'] ? $athleteData['identity_document']->store('identity_docs', 'public') : null;

                // Create or Update Master Athlete Data
                $athlete = Athlete::updateOrCreate(
                    ['nik' => $athleteData['nik']],
                    [
                        'name' => $athleteData['name'],
                        'gender' => $athleteData['gender'],
                        'birth_date' => $athleteData['birth_date'],
                        'bpjs_number' => $athleteData['bpjs_number'],
                        'bpjs_status' => $athleteData['bpjs_status'],
                        'bpjs_card_path' => $bpjsPath ?? Athlete::where('nik', $athleteData['nik'])->value('bpjs_card_path'),
                        'identity_document_path' => $identityPath ?? Athlete::where('nik', $athleteData['nik'])->value('identity_document_path'),
                    ]
                );

                // Update Membership & Record History if changing contingent
                $currentPrimary = $athlete->contingents()->wherePivot('is_primary', true)->first();
                if (!$currentPrimary || $currentPrimary->id !== $contingent->id) {
                    // Mark old as not primary
                    $athlete->contingents()->updateExistingPivot($currentPrimary?->id, ['is_primary' => false]);
                    
                    // Attach new as primary
                    $athlete->contingents()->syncWithoutDetaching([
                        $contingent->id => [
                            'is_primary' => true,
                            'joined_at' => now(),
                        ]
                    ]);

                    // Log History
                    $athlete->contingentHistories()->create([
                        'contingent_id' => $contingent->id,
                        'moved_at' => now(),
                        'notes' => 'Pendaftaran Turnamen ' . $this->contingent_name,
                    ]);
                }

                // Attach to Registration with Tournament-specific data
                $ageGroup = $this->ageGroups->firstWhere('id', $athleteData['age_group']);

                $registration->athletes()->attach($athlete->id, [
                    'weight' => $athleteData['current_weight'] ?? 0,
                    'weight_group_id' => $athleteData['weight_group_id'] ?? null,
                    'kyu' => $athleteData['rank'] ?? '',
                    'age_group' => $ageGroup?->name ?? $athleteData['age_group'],
                    'rank' => $athleteData['rank'],
                    'dojo_origin' => $athleteData['dojo_origin'],
                    'city' => $athleteData['city'],
                    'match_type' => $athleteData['match_type'] ?? 'Tanding',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Attach Match Numbers with registration context
                foreach (['event1', 'event2', 'event3'] as $fld) {
                    $matchNumberId = $athleteData[$fld];
                    if ($matchNumberId) {
                        $athlete->matchNumbers()->attach($matchNumberId, [
                            'registration_id' => $registration->id,
                            'technique_ids' => json_encode($this->matchTechniques[$matchNumberId] ?? []),
                        ]);
                    }
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
