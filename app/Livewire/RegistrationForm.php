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
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Registration;
use App\Models\Technique\Technique;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public $leader_phone;

    public $leader_email;

    public $address;

    public $transfer_proof;

    public $payment_method_detail = null;

    public bool $is_authenticated = false;

    public $registration_id = null;

    // B. OFFICIAL
    public array $officials = [
        ['official_id' => '', 'name' => '', 'role' => '', 'phone' => ''],
    ];

    // C. ATLET
    public array $athletes = [
        [
            'athlete_id' => '',
            'nik' => '',
            'nik_kenshi' => '',
            'name' => '',
            'gender' => 'Male',
            'birth_place' => '',
            'blood_type' => '',
            'birth_date' => '',
            'address' => '',
            'phone' => '',
            'photo' => null,
            'existing_photo_path' => null, // Existing DB path; if set, new upload is optional
            'current_weight' => '',
            'weight_group_id' => '',
            'age_group' => '',
            'rank' => 'Kyu 6',
            'dojo_origin' => '',
            'city' => '',
            'bpjs_number' => '',
            'bpjs_status' => 'Aktif',
            'bpjs_card' => null,
            'event1' => '',
            'event2' => '',
            'event3' => '',
            'identity_document' => null,
            'is_master_found' => false,
            'show_fields' => false,
        ],
    ];

    public array $matchTechniques = [];

    public $weightGroups;

    public $masterAthletes;

    public $masterOfficials;

    public $ageGroups;

    // D. PERNYATAAN
    public string $sim_perkemi_confirm = 'Ya';

    public $kyuLevels;

    public $techniques;

    public bool $is_success = false;

    // Payment & Pricing
    public string $payment_method = 'Tunai';

    public bool $is_readonly = false;

    public int $unique_code = 0;

    public string $referral_code = '';

    public int $contingent_fee = 2500000;

    public int $athlete_fee_pemula = 400000;

    public int $athlete_fee_lainnya = 500000;

    public function mount()
    {
        if (! Auth::check()) {
            return redirect()->route('new-login');
        }

        $this->unique_code = rand(100, 999);
        $this->kyuLevels = KyuLevel::orderBy('order', 'asc')->get();
        $this->weightGroups = WeightGroup::orderBy('order', 'asc')->get();
        $this->ageGroups = AgeGroup::orderBy('order', 'asc')->get();
        $this->techniques = Technique::orderBy('order', 'asc')->get();

        // Set default age_group for first athlete
        if ($this->ageGroups->isNotEmpty()) {
            $this->athletes[0]['age_group'] = $this->ageGroups->first()->id;
        }

        // Initial state: No master athletes for guests (Privacy)
        $this->masterAthletes = collect();
        $this->masterOfficials = collect();

        if (auth()->check()) {
            $contingent = auth()->user()->contingent;
            if ($contingent) {
                $this->loadContingentData($contingent);
                // Load ONLY athletes from this contingent
                $this->masterAthletes = $contingent->athletes()
                    ->orderBy('name', 'asc')
                    ->get(['athletes.id', 'athletes.name', 'athletes.nik']);
                $this->masterOfficials = Official::where('contingent_id', auth()->user()->contingent?->id)->get(['id', 'name']);
            }
        }

        if (request()->has('draft_id')) {
            $this->loadDraft(request()->query('draft_id'));
        }
    }

    public function loadDraft($id)
    {
        $reg = Registration::find($id);

        if ($reg && $reg->status === 'verified') {
            $this->is_readonly = true;
        }

        if ($reg && in_array($reg->status, ['draft', 'pending'])) {
            $this->is_readonly = false;
        }

        if ($reg) {
            $this->registration_id = $reg->id;
            $this->unique_code = $reg->unique_code;

            if ($reg->contingent) {
                $this->loadContingentData($reg->contingent);
            }

            $draft = json_decode($reg->draft_data, true);
            if ($draft) {
                // Load contingent fields from draft if available
                if (isset($draft['contingent_name'])) {
                    $this->contingent_name = $draft['contingent_name'];
                }
                if (isset($draft['contingent_city'])) {
                    $this->contingent_city = $draft['contingent_city'];
                }
                if (isset($draft['leader_name'])) {
                    $this->leader_name = $draft['leader_name'];
                }
                if (isset($draft['leader_phone'])) {
                    $this->leader_phone = $draft['leader_phone'];
                }
                if (isset($draft['leader_email'])) {
                    $this->leader_email = $draft['leader_email'];
                }
                if (isset($draft['address'])) {
                    $this->address = $draft['address'];
                }

                if (isset($draft['officials']) && count($draft['officials']) > 0) {
                    $this->officials = $draft['officials'];
                }
                if (isset($draft['athletes']) && count($draft['athletes']) > 0) {
                    $this->athletes = $draft['athletes'];
                }
                if (isset($draft['matchTechniques'])) {
                    $this->matchTechniques = $draft['matchTechniques'];
                }
                if (isset($draft['payment_method'])) {
                    $this->payment_method = $draft['payment_method'];
                    $this->getPaymentMethodDetail();
                }
            } else {
                $this->loadDataFromRelationships($reg);
            }
        }
    }

    public function loadDataFromRelationships($reg)
    {
        // 1. Load Officials
        $this->officials = $reg->officials->map(function ($off) {
            return [
                'official_id' => $off->id,
                'name' => $off->name,
                'role' => $off->pivot->role,
                'phone' => $off->phone,
            ];
        })->toArray();

        if (empty($this->officials)) {
            $this->officials = [['official_id' => '', 'name' => '', 'role' => '', 'phone' => '']];
        }

        // 2. Load Athletes & Match Numbers
        $this->athletes = $reg->athletes->map(function ($ath) use ($reg) {
            $matchNumbers = DB::table('athlete_match_number')
                ->where('athlete_id', $ath->id)
                ->where('registration_id', $reg->id)
                ->get();

            $athData = [
                'athlete_id' => $ath->id,
                'nik' => $ath->nik,
                'nik_kenshi' => $ath->nik_kenshi,
                'name' => $ath->name,
                'gender' => $ath->gender,
                'birth_place' => $ath->birth_place,
                'blood_type' => $ath->blood_type,
                'birth_date' => $ath->birth_date instanceof Carbon ? $ath->birth_date->format('Y-m-d') : Carbon::parse($ath->birth_date)->format('Y-m-d'),
                'address' => $ath->address,
                'phone' => $ath->phone,
                'photo' => null,
                'existing_photo_path' => $ath->photo_path, // Keep existing path so re-upload is optional
                'current_weight' => $ath->pivot->weight,
                'weight_group_id' => $ath->pivot->weight_group_id,
                'age_group' => $this->ageGroups->firstWhere('name', $ath->pivot->age_group)?->id ?? $ath->pivot->age_group,
                'rank' => $ath->pivot->rank,
                'dojo_origin' => $ath->pivot->dojo_origin,
                'city' => $ath->pivot->city,
                'bpjs_number' => $ath->bpjs_number,
                'bpjs_status' => $ath->bpjs_status,
                'bpjs_card' => null,
                'identity_document' => null,
                'is_master_found' => true,
                'show_fields' => true,
            ];

            // Map match numbers to event1, event2, event3
            foreach ($matchNumbers as $i => $mn) {
                $field = 'event'.($i + 1);
                if ($i < 3) {
                    $athData[$field] = $mn->match_number_id;
                    $this->matchTechniques[$mn->match_number_id] = json_decode($mn->technique_ids, true) ?? [];
                }
            }

            // Fill empty events
            for ($i = count($matchNumbers); $i < 3; $i++) {
                $athData['event'.($i + 1)] = '';
            }

            return $athData;
        })->toArray();

        if (empty($this->athletes)) {
            $this->athletes = [
                [
                    'athlete_id' => '',
                    'nik' => '',
                    'nik_kenshi' => '',
                    'name' => '',
                    'gender' => 'Male',
                    'birth_place' => '',
                    'blood_type' => '',
                    'birth_date' => '',
                    'address' => '',
                    'phone' => '',
                    'photo' => null,
                    'current_weight' => '',
                    'weight_group_id' => '',
                    'age_group' => '',
                    'rank' => 'Kyu 6',
                    'dojo_origin' => '',
                    'city' => '',
                    'bpjs_number' => '',
                    'bpjs_status' => 'Aktif',
                    'bpjs_card' => null,
                    'event1' => '',
                    'event2' => '',
                    'event3' => '',
                    'identity_document' => null,
                    'is_master_found' => false,
                    'show_fields' => false,
                ],
            ];
        }
    }

    public function loadContingentData($contingent)
    {
        if ($contingent) {
            $this->is_authenticated = true;
            $this->contingent_id = $contingent->id;
            $this->contingent_name = $contingent?->name;
            $this->contingent_city = $contingent?->kab_kota;
            $this->leader_name = $contingent?->leader_name;
            $this->leader_phone = $contingent?->leader_phone;
            $this->leader_email = $contingent?->email;
            $this->address = $contingent?->address;
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
            'athletes.*.birth_place' => 'required',
            'athletes.*.birth_date' => 'required',
            'athletes.*.blood_type' => 'nullable',
            'athletes.*.address' => 'required',
            'athletes.*.phone' => 'nullable',
            'athletes.*.age_group' => 'required',
            'athletes.*.rank' => 'required',
            'athletes.*.dojo_origin' => 'required',
            'athletes.*.nik' => 'required|numeric|digits:16',
            'athletes.*.nik_kenshi' => 'nullable|string',
            'athletes.*.bpjs_number' => 'required|numeric',
            'athletes.*.bpjs_status' => 'required|in:Aktif',
            'athletes.*.current_weight' => 'required|numeric',
            'athletes.*.weight_group_id' => 'required',
            'athletes.*.bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'athletes.*.identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'athletes.*.existing_photo_path' => 'nullable|string',
            'athletes.*.photo' => [
                // Only require a new upload if there is no existing photo stored in the DB
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $existingPath = $this->athletes[$index]['existing_photo_path'] ?? null;
                    if (empty($existingPath) && empty($value)) {
                        $fail('Foto profil wajib diunggah.');
                    }
                },
                'nullable',
                'image',
                'max:2048',
            ],
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
            $prefix = 'Official #'.($index + 1);
            $attributes["officials.{$index}.name"] = "{$prefix} Nama";
            $attributes["officials.{$index}.role"] = "{$prefix} Jabatan";
        }

        foreach ($this->athletes as $index => $athlete) {
            $prefix = 'Atlet #'.($index + 1);
            $attributes["athletes.{$index}.name"] = "{$prefix} Nama Lengkap";
            $attributes["athletes.{$index}.nik"] = "{$prefix} NIK";
            $attributes["athletes.{$index}.nik_kenshi"] = "{$prefix} NIK Kenshi";
            $attributes["athletes.{$index}.age_group"] = "{$prefix} Kelompok Usia";
            $attributes["athletes.{$index}.rank"] = "{$prefix} Tingkatan (Rank)";
            $attributes["athletes.{$index}.dojo_origin"] = "{$prefix} Asal Dojo";
            $attributes["athletes.{$index}.bpjs_number"] = "{$prefix} Nomor BPJS";
            $attributes["athletes.{$index}.current_weight"] = "{$prefix} Berat Badan";
            $attributes["athletes.{$index}.weight_group_id"] = "{$prefix} Kelompok Berat";
        }

        return $attributes;
    }

    public function updatedOfficials($value, $key)
    {
        if (str_contains($key, 'official_id')) {
            $parts = explode('.', $key);
            $index = $parts[0];
            $officialId = $value;

            if ($officialId === 'new') {
                $this->officials[$index]['official_id'] = '';
                $this->officials[$index]['role'] = '';
                $this->officials[$index]['name'] = '';
                $this->officials[$index]['phone'] = '';

                return;
            }

            if (! empty($officialId)) {
                $official = Official::find($officialId);
                if ($official) {
                    $this->officials[$index]['official_id'] = $official->id;
                    $this->officials[$index]['name'] = $official->name;
                    $this->officials[$index]['role'] = $official->role;
                    $this->officials[$index]['phone'] = $official->phone;
                }
            }
        }
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

            if (! empty($athleteId)) {
                $athlete = Athlete::find($athleteId);
                if ($athlete) {
                    $this->athletes[$index]['nik'] = $athlete->nik;
                    $this->athletes[$index]['nik_kenshi'] = $athlete->nik_kenshi;
                    $this->athletes[$index]['name'] = $athlete->name;
                    $this->athletes[$index]['gender'] = $athlete->gender;
                    $this->athletes[$index]['birth_place'] = $athlete->birth_place;
                    $this->athletes[$index]['blood_type'] = $athlete->blood_type;
                    $this->athletes[$index]['birth_date'] = Carbon::parse($athlete->birth_date)->format('Y-m-d');
                    $this->athletes[$index]['address'] = $athlete->address;
                    $this->athletes[$index]['dojo_origin'] = $athlete->dojo_origin;
                    $this->athletes[$index]['phone'] = $athlete->phone;
                    $this->athletes[$index]['bpjs_number'] = $athlete->bpjs_number;
                    $this->athletes[$index]['bpjs_status'] = $athlete->bpjs_status;
                    $this->athletes[$index]['is_master_found'] = true;
                    $this->athletes[$index]['show_fields'] = true;
                }
            }
        }

        // 2. Check if an event category was updated
        if (str_contains($key, 'event1') || str_contains($key, 'event2') || str_contains($key, 'event3')) {
            if ($value && ! isset($this->matchTechniques[$value])) {
                $this->matchTechniques[$value] = [];
            }
        }
    }

    public function addTechniqueToMatch($matchId, $techniqueValue)
    {
        if (empty($matchId) || empty($techniqueValue)) {
            return;
        }

        $technique = null;

        // Check if $techniqueValue is an ID or a new name
        if (is_numeric($techniqueValue)) {
            $technique = Technique::find($techniqueValue);
        } else {
            // It's a new name, create it
            $technique = Technique::firstOrCreate(
                ['name' => $techniqueValue],
                ['order' => Technique::max('order') + 1]
            );
            // Refresh the techniques list for the select
            $this->techniques = Technique::orderBy('order', 'asc')->get();
        }

        if (! $technique) {
            return;
        }

        if (! isset($this->matchTechniques[$matchId])) {
            $this->matchTechniques[$matchId] = [];
        }

        // Prevent duplicates
        if (in_array($technique->id, $this->matchTechniques[$matchId])) {
            return;
        }

        $this->matchTechniques[$matchId][] = $technique->id;
    }

    public function removeTechniqueFromMatch($matchId, $index)
    {
        if (isset($this->matchTechniques[$matchId][$index])) {
            unset($this->matchTechniques[$matchId][$index]);
            $this->matchTechniques[$matchId] = array_values($this->matchTechniques[$matchId]);
        }
    }

    public function getMatchLeaderInfo($matchId)
    {
        if (empty($matchId)) {
            return null;
        }

        foreach ($this->athletes as $index => $athlete) {
            foreach (['event1', 'event2', 'event3'] as $fld) {
                if ($athlete[$fld] == $matchId) {
                    return [
                        'athlete_index' => $index,
                        'athlete_name' => $athlete['name'] ?: 'Atlet #'.($index + 1),
                        'field' => $fld,
                    ];
                }
            }
        }

        return null;
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
            $this->athletes[$index]['nik_kenshi'] = $athlete->nik_kenshi;
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

            $this->dispatch('swal', title: 'Data Baru', text: 'NIK tidak terdaftar. Silakan lengkapi data atlet baru.', icon: 'info');
        }
    }

    public function addOfficial()
    {
        $this->officials[] = ['official_id' => '', 'name' => '', 'role' => '', 'phone' => ''];
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
            'athlete_id' => '',
            'nik' => '',
            'nik_kenshi' => '',
            'name' => '',
            'gender' => 'Male',
            'birth_place' => '',
            'blood_type' => '',
            'birth_date' => '',
            'address' => '',
            'phone' => '',
            'photo' => null,
            'existing_photo_path' => null,
            'current_weight' => '',
            'weight_group_id' => '',
            'age_group' => '',
            'rank' => 'Kyu 6',
            'dojo_origin' => '',
            'city' => '',
            'bpjs_number' => '',
            'bpjs_status' => 'Aktif',
            'bpjs_card' => null,
            'event1' => '',
            'event2' => '',
            'event3' => '',
            'identity_document' => null,
            'is_master_found' => false,
            'show_fields' => false,
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
        if (empty($ageGroupId)) {
            return [];
        }

        // Robust fallback: if $ageGroupId is a name (e.g. from legacy draft), find the ID
        if ($ageGroupId && ! is_numeric($ageGroupId)) {
            $ageGroupId = AgeGroup::where('name', $ageGroupId)->value('id') ?? $ageGroupId;
        }

        return MatchNumber::where('age_group_id', $ageGroupId)
            ->where(function ($query) use ($gender) {
                $query->where('gender', $gender)
                    ->orWhere('gender', 'Mix');
            })
            ->withCount([
                'athletes' => function ($query) {
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
                },
            ])
            ->orderBy('created_at', 'asc')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->filter(function ($matchNumber) use ($currentAthleteIndex, $currentField) {
                // 1. Get DB Count (Now scoped to THIS contingent)
                $dbCount = $matchNumber->athletes_count;

                // 2. Check if this specific athlete has ALREADY picked this category in another slot
                if ($currentAthleteIndex !== null) {
                    foreach (['event1', 'event2', 'event3'] as $fld) {
                        if ($fld === $currentField) {
                            continue;
                        }
                        if ($this->athletes[$currentAthleteIndex][$fld] == $matchNumber->id) {
                            return false; // Already selected by this athlete in another slot
                        }
                    }
                }

                // 3. Get Current Form Count (from other athletes)
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

                // Always include the currently selected value so the dropdown doesn't reset
                if ($currentAthleteIndex !== null && $currentField !== null && $this->athletes[$currentAthleteIndex][$currentField] == $matchNumber->id) {
                    return true;
                }

                // 3. Logic: If max_athletes > 0, check if we have space (per contingent)
                return $matchNumber->max_athletes == 0 || $totalOccupied < $matchNumber->max_athletes;
            })
            ->values();
    }

    public function isEmbu($matchId)
    {
        if (empty($matchId)) {
            return false;
        }

        return MatchNumber::find($matchId)?->draft_type === 'embu';
    }

    public function getAthletePemulaCount()
    {
        $count = 0;
        foreach ($this->athletes as $athlete) {
            $ageGroupId = $athlete['age_group'];
            $name = is_numeric($ageGroupId)
                ? ($this->ageGroups->firstWhere('id', $ageGroupId)?->name)
                : $ageGroupId;

            if ($name === 'Pemula') {
                $count++;
            }
        }

        return $count;
    }

    public function getAthleteLainnyaCount()
    {
        $count = 0;
        foreach ($this->athletes as $athlete) {
            $ageGroupId = $athlete['age_group'];
            $name = is_numeric($ageGroupId)
                ? ($this->ageGroups->firstWhere('id', $ageGroupId)?->name)
                : $ageGroupId;

            if ($name !== 'Pemula' && ! empty($name)) {
                $count++;
            }
        }

        return $count;
    }

    public function getTotalAthleteFee()
    {
        $total = 0;
        foreach ($this->athletes as $athleteData) {
            $ageGroupId = $athleteData['age_group'];
            // Handle name-based age group (legacy or draft)
            if ($ageGroupId && ! is_numeric($ageGroupId)) {
                $ageGroupId = $this->ageGroups->firstWhere('name', $ageGroupId)?->id;
            }

            $ageGroup = $this->ageGroups->firstWhere('id', $ageGroupId);
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
                if ($athlete[$field]) {
                    $matchIds[] = $athlete[$field];
                }
            }
        }

        if (empty($matchIds)) {
            return [];
        }

        $matches = MatchNumber::whereIn('id', array_unique($matchIds))->with('ageGroup')->get()->keyBy('id');
        $allTechniques = Technique::pluck('name', 'id')->toArray();

        // Organize into: [Gender][AgeGroupName][MatchID]
        foreach ($matchIds as $mId) {
            if (isset($matches[$mId])) {
                $match = $matches[$mId];
                $gender = $match->gender;
                $ageGroupName = $match->ageGroup?->name ?? 'N/A';

                if (! isset($summary[$gender])) {
                    $summary[$gender] = [];
                }

                if (! isset($summary[$gender][$ageGroupName])) {
                    $summary[$gender][$ageGroupName] = [];
                }

                if (! isset($summary[$gender][$ageGroupName][$mId])) {
                    // Resolve tech names
                    $selectedTechs = $this->matchTechniques[$mId] ?? [];
                    if (! is_array($selectedTechs)) {
                        $selectedTechs = [];
                    }

                    $techNames = [];
                    foreach ($selectedTechs as $tid) {
                        if (isset($allTechniques[$tid])) {
                            $techNames[] = $allTechniques[$tid];
                        }
                    }

                    $summary[$gender][$ageGroupName][$mId] = [
                        'name' => $match->name,
                        'draft_type' => $match->draft_type,
                        'techniques' => $techNames,
                        'athletes' => [],
                    ];
                }
            }
        }

        // Add Athletes to Matches with Rank
        foreach ($this->athletes as $athlete) {
            if (empty($athlete['name'])) {
                continue;
            }

            foreach (['event1', 'event2', 'event3'] as $field) {
                $mId = $athlete[$field];
                if ($mId && isset($matches[$mId])) {
                    $match = $matches[$mId];
                    $gender = $match->gender;
                    $ageGroupName = $match->ageGroup?->name ?? 'N/A';

                    if (isset($summary[$gender][$ageGroupName][$mId])) {
                        $summary[$gender][$ageGroupName][$mId]['athletes'][] = [
                            'name' => $athlete['name'],
                            'rank' => $athlete['rank'] ?? 'N/A',
                            'weight' => $athlete['current_weight'] ?? '-',
                            'weight_group' => $this->weightGroups->firstWhere('id', $athlete['weight_group_id'])?->name ?? '-',
                        ];
                    }
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

            if (! $this->is_authenticated) {
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
                    'official_id' => $user->id,
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

            // 4. Create or Update Registration (Formulir) Transactional Data
            $transferPath = $this->transfer_proof ? $this->transfer_proof->store('transfer_proofs', 'public') : null;

            if ($this->registration_id) {
                $registration = Registration::find($this->registration_id);

                if ($registration && $registration->status === 'verified') {
                    throw new \Exception('Data pendaftaran ini sudah tidak dapat diubah karena sudah diverifikasi oleh admin.');
                }

                // Clean up file uploads from array before JSON encoding for the final draft state
                $athletesData = $this->athletes;
                foreach ($athletesData as &$ath) {
                    $ath['photo'] = null;
                    $ath['bpjs_card'] = null;
                    $ath['identity_document'] = null;
                }

                $draftData = [
                    'contingent_name' => $this->contingent_name,
                    'contingent_city' => $this->contingent_city,
                    'leader_name' => $this->leader_name,
                    'leader_phone' => $this->leader_phone,
                    'leader_email' => $this->leader_email,
                    'address' => $this->address,
                    'officials' => $this->officials,
                    'athletes' => $athletesData,
                    'matchTechniques' => $this->matchTechniques,
                    'payment_method' => $this->payment_method,
                ];

                $registration->update([
                    'total_cost' => $this->getTotalProperty(),
                    'final_amount' => $this->getFinalTotalProperty(),
                    'payment_method' => $this->payment_method,
                    'status' => 'pending',
                    'draft_data' => json_encode($draftData),
                    'sim_perkemi_confirm' => $this->sim_perkemi_confirm,
                ]);
                if ($transferPath) {
                    $registration->update(['transfer_proof_path' => $transferPath]);
                }

                // Clear existing relationships before re-attaching
                $registration->officials()->detach();
                $registration->athletes()->detach();
                DB::table('athlete_match_number')->where('registration_id', $registration->id)->delete();

            } else {
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
            }

            $this->referral_code = $registration->referral_code;

            // 5. Link Officials to Registration (Find or Create Master)
            foreach ($this->officials as $officialData) {
                $officialData['contingent_id'] = $contingentId;
                $official = Official::updateOrCreate(
                    ['id' => ! empty($officialData['official_id']) ? $officialData['official_id'] : null],
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
                $bpjsPath = ($athleteData['bpjs_card'] ?? null) ? $athleteData['bpjs_card']->store('bpjs_cards', 'public') : null;
                $identityPath = ($athleteData['identity_document'] ?? null) ? $athleteData['identity_document']->store('identity_docs', 'public') : null;
                $photoPath = ($athleteData['photo'] ?? null) ? $athleteData['photo']->store('athlete_photos', 'public') : null;

                // Create or Update Master Athlete Data
                $athlete = Athlete::updateOrCreate(
                    ['nik' => $athleteData['nik']],
                    [
                        'name' => $athleteData['name'],
                        'nik_kenshi' => $athleteData['nik_kenshi'] ?? null,
                        'gender' => $athleteData['gender'],
                        'birth_place' => $athleteData['birth_place'],
                        'blood_type' => $athleteData['blood_type'],
                        'birth_date' => $athleteData['birth_date'],
                        'address' => $athleteData['address'],
                        'dojo_origin' => $athleteData['dojo_origin'],
                        'phone' => $athleteData['phone'],
                        'bpjs_number' => $athleteData['bpjs_number'],
                        'bpjs_status' => $athleteData['bpjs_status'],
                        'photo_path' => $photoPath ?? Athlete::where('nik', $athleteData['nik'])?->value('photo_path'),
                        'bpjs_card_path' => $bpjsPath ?? Athlete::where('nik', $athleteData['nik'])?->value('bpjs_card_path'),
                        'identity_document_path' => $identityPath ?? Athlete::where('nik', $athleteData['nik'])?->value('identity_document_path'),
                    ]
                );

                // Update Membership & Record History if changing contingent
                $currentPrimary = $athlete->contingents()->wherePivot('is_primary', true)->first();
                if (! $currentPrimary || $currentPrimary->id !== $contingent->id) {
                    // Mark old as not primary
                    $athlete->contingents()->updateExistingPivot($currentPrimary?->id, ['is_primary' => false]);

                    // Attach new as primary
                    $athlete->contingents()->syncWithoutDetaching([
                        $contingent->id => [
                            'is_primary' => true,
                            'joined_at' => now(),
                        ],
                    ]);

                    // Log History
                    $athlete->contingentHistories()->create([
                        'contingent_id' => $contingent->id,
                        'moved_at' => now(),
                        'notes' => 'Pendaftaran Turnamen '.$this->contingent_name,
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

    public function updatedPaymentMethod($value)
    {
        $this->payment_method = $value;
        $this->getPaymentMethodDetail();
    }

    public function getPaymentMethodDetail()
    {
        $this->payment_method_detail = PaymentMethod::where('bank', $this->payment_method)->first();
    }

    public function saveDraft()
    {
        $this->validate([
            'contingent_name' => 'required|min:3',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'leader_email' => 'required|email',
        ]);

        DB::transaction(function () {
            $contingentId = $this->contingent_id;

            if (! $this->is_authenticated) {
                $tempPassword = Str::random(12);
                $user = User::create([
                    'name' => $this->contingent_name,
                    'email' => $this->leader_email,
                    'password' => Hash::make($tempPassword),
                ]);
                $user->assignRole('Contingent');

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

                // Automatically login the new user so they can manage their draft
                auth()->login($user);
                $this->is_authenticated = true;
                $this->contingent_id = $contingentId;
            } else {
                // Update existing contingent info
                $contingent = Contingent::find($contingentId);
                if ($contingent) {
                    $contingent->update([
                        'name' => $this->contingent_name,
                        'kab_kota' => $this->contingent_city,
                        'leader_name' => $this->leader_name,
                        'leader_phone' => $this->leader_phone,
                        'email' => $this->leader_email,
                        'address' => $this->address,
                    ]);
                }
            }

            // Clean up file uploads from array before JSON encoding
            $athletesData = $this->athletes;
            foreach ($athletesData as &$ath) {
                if (isset($ath['photo']) && is_object($ath['photo'])) {
                    $ath['photo_path'] = $ath['photo']->store('athlete_photos', 'public');
                    $ath['photo'] = null;
                }
                if (isset($ath['bpjs_card']) && is_object($ath['bpjs_card'])) {
                    $ath['bpjs_card_path'] = $ath['bpjs_card']->store('bpjs_cards', 'public');
                    $ath['bpjs_card'] = null;
                }
                if (isset($ath['identity_document']) && is_object($ath['identity_document'])) {
                    $ath['identity_document_path'] = $ath['identity_document']->store('identity_docs', 'public');
                    $ath['identity_document'] = null;
                }

                // Ensure keys exist even if not uploaded now
                $ath['photo'] = $ath['photo'] ?? null;
                $ath['bpjs_card'] = $ath['bpjs_card'] ?? null;
                $ath['identity_document'] = $ath['identity_document'] ?? null;
            }

            $draftData = [
                'contingent_name' => $this->contingent_name,
                'contingent_city' => $this->contingent_city,
                'leader_name' => $this->leader_name,
                'leader_phone' => $this->leader_phone,
                'leader_email' => $this->leader_email,
                'address' => $this->address,
                'officials' => $this->officials,
                'athletes' => $athletesData,
                'matchTechniques' => $this->matchTechniques,
                'payment_method' => $this->payment_method,
            ];

            if (is_object($this->transfer_proof)) {
                $draftData['transfer_proof_path'] = $this->transfer_proof->store('transfer_proofs', 'public');
            }

            if ($this->registration_id) {
                $reg = Registration::find($this->registration_id);

                if ($reg && $reg->status === 'verified') {
                    throw new \Exception('Data pendaftaran ini sudah tidak dapat diubah karena sudah diverifikasi oleh admin.');
                }

                $reg->update([
                    'draft_data' => json_encode($draftData),
                    'total_cost' => $this->getTotalProperty(),
                    'final_amount' => $this->getFinalTotalProperty(),
                ]);
                $this->referral_code = $reg->referral_code;
            } else {
                $registration = Registration::create([
                    'contingent_id' => $contingentId,
                    'total_cost' => $this->getTotalProperty(),
                    'final_amount' => $this->getFinalTotalProperty(),
                    'unique_code' => $this->unique_code,
                    'status' => 'draft',
                    'draft_data' => json_encode($draftData),
                ]);
                $this->registration_id = $registration->id;
                $this->referral_code = 'DRAFT-'.strtoupper(Str::random(5));
                $registration->update(['referral_code' => $this->referral_code]);
            }
        });

        $this->is_success = true;
    }

    public function render()
    {
        return view('livewire.registration-form', [
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
