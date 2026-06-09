<div>
    @push('styles')
    <style>
    .sch-hero { background: var(--ink); padding: 24px 20px 28px; position: relative; overflow: hidden; }
    .sch-hero::before {
        content: ''; position: absolute; top: -60px; right: -60px; width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(192, 57, 43, .4) 0%, transparent 70%); pointer-events: none;
    }
    .sch-hero-label { font-size: 9.5px; color: var(--smoke); font-weight: 700; letter-spacing: .2em; text-transform: uppercase; margin-bottom: 8px; }
    .sch-hero-title { font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700; color: #fff; margin: 0 0 4px; }
    .sch-hero-sub { font-size: 12px; color: var(--smoke); margin: 0; }

    .ctg-page-body { padding: 16px; }

    /* ALERT BANNERS */
    .status-alert { padding: 16px; border-radius: 14px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 12px; font-size: 13px; line-height: 1.4; border: 1px solid transparent; }
    .status-alert.verified { background: rgba(39,174,96,.08); border-color: rgba(39,174,96,.2); color: #1e8449; }
    .status-alert.pending { background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.2); color: #d97706; }
    .status-alert i { font-size: 16px; margin-top: 2px; }

    .status-badge-inline { display: inline-flex; align-items: center; gap: 5px; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .status-badge-inline.verified { background: #27ae60; color: #fff; }
    .status-badge-inline.pending { background: #f59e0b; color: #fff; }

    /* CARDS */
    .ctg-card { background: #fff; border-radius: 18px; border: 1px solid var(--paper2); padding: 18px; margin-bottom: 16px; }
    .ctg-card h3 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--ink); margin: 0 0 14px; border-bottom: 1px solid var(--paper); padding-bottom: 8px; }

    /* FORM SELECT */
    .select-input { padding: 8px 12px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 13px; background: #fff; color: var(--ink); width: 100%; max-width: 320px; outline: none; }

    /* TABLE */
    .ath-table-wrap { overflow-x: auto; margin-bottom: 16px; }
    .ath-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .ath-table th { font-size: 10px; color: var(--smoke); text-transform: uppercase; letter-spacing: .05em; padding: 10px; text-align: left; background: var(--paper); border-bottom: 1px solid var(--paper2); }
    .ath-table td { padding: 11px 10px; font-size: 12.5px; border-bottom: 1px solid var(--paper2); color: var(--ink); }

    .act-btn {
      width: 28px; height: 28px; border-radius: 6px;
      border: 1px solid var(--paper2); background: #fff;
      cursor: pointer; font-size: 11px; color: #555;
      display: inline-flex; align-items: center; justify-content: center;
      transition: all .15s; text-decoration: none;
    }
    .act-btn:hover { background: var(--paper2); }
    .act-btn.view  { color: #2980b9; border-color: rgba(41,128,185,.2); background: rgba(41,128,185,.05); }

    /* MODAL */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
    .modal-card { background: #fff; border-radius: 16px; width: 100%; max-width: 580px; border: 1px solid var(--paper2); box-shadow: 0 20px 50px rgba(0,0,0,.15); overflow: hidden; animation: slideUp .25s ease-out; }
    .modal-header { padding: 16px 20px; background: var(--ink); color: #fff; display: flex; align-items: center; justify-content: space-between; }
    .modal-header h3 { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; margin: 0; color: var(--gold-lt); }
    .modal-body { padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; max-height: 480px; overflow-y: auto; }
    .modal-footer { padding: 14px 20px; background: var(--paper); border-top: 1px solid var(--paper2); display: flex; justify-content: flex-end; gap: 8px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.span-2 { grid-column: span 2; }
    .form-group label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--smoke); letter-spacing: .05em; }
    .form-group input, .form-group select { padding: 8px 12px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 13px; font-family: 'DM Sans', sans-serif; outline: none; }
    .form-group input:focus, .form-group select:focus { border-color: var(--ink); }

    .btn-prem { padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: none; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
    .btn-prem.primary { background: var(--ink); color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .btn-prem.primary:hover { background: #000; }
    .btn-prem.secondary { background: var(--paper2); color: var(--ink); }
    .btn-prem.secondary:hover { background: var(--paper); }
    .btn-prem.success { background: #27ae60; color: #fff; box-shadow: 0 4px 12px rgba(39,174,96,.25); }
    .btn-prem.success:hover { background: #1e8449; }

    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
    @endpush

    {{-- HERO --}}
    <div class="sch-hero">
        <div class="sch-hero-label">Verifikasi Data Kenshi</div>
        <h2 class="sch-hero-title">{{ $contingent->name }}</h2>
        <p class="sch-hero-sub">{{ $contingent->kab_kota }}</p>
    </div>

    <div class="ctg-page-body">
        @if($activeRegistration)
            {{-- STATUS ALERT BANNER --}}
            @if($activeRegistration->athlete_status === 'verified')
                <div class="status-alert verified">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Data Atlet Terverifikasi!</strong> data atlet Anda telah diverifikasi oleh Panitia dan sudah dimasukkan dalam <strong>Drawing Technical Meeting</strong>.
                        <br><span style="font-size:11px;opacity:.9;">Catatan: Anda tetap dapat mengubah data atlet jika diperlukan, namun hal ini akan me-reset status verifikasi menjadi PENDING dan membutuhkan verifikasi ulang.</span>
                    </div>
                </div>
            @else
                <div class="status-alert pending">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <strong>Menunggu Verifikasi Data Atlet!</strong> data atlet Anda saat ini dalam antrian peninjauan oleh Panitia.
                        <br><span style="font-size:11px;opacity:.9;">Pastikan seluruh data (Dojo, Berat Badan, Kyu, berkas BPJS/Foto) sudah lengkap dan sesuai. Klik tombol "Konfirmasi Data Sudah Sesuai" di bawah jika data sudah siap diperiksa.</span>
                    </div>
                </div>
            @endif

            {{-- SELECT REGISTRATION --}}
            @if($registrations->count() > 1)
                <div class="ctg-card">
                    <h3 style="margin-bottom:8px;">Pilih Transaksi Pendaftaran</h3>
                    <select wire:model.live="selectedRegistrationId" class="select-input">
                        @foreach($registrations as $reg)
                            <option value="{{ $reg->id }}">{{ $reg->referral_code }} ({{ $reg->created_at->format('d M Y') }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- ATHLETE LIST --}}
            <div class="ctg-card">
                <h3>Data Atlet Terdaftar</h3>
                
                <div class="ath-table-wrap">
                    <table class="ath-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama / NIK</th>
                                <th>Dojo Origin</th>
                                <th>BB (Kg)</th>
                                <th>Kyu / Rank</th>
                                <th>Nomor Tanding</th>
                                <th>BPJS</th>
                                <th>Berkas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeRegistration->athletes as $idx => $ath)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <div style="font-weight:700;">{{ $ath->name }}</div>
                                    <div style="font-size:11px;color:var(--smoke);font-family:monospace;">
                                        NIK: {{ $ath->nik }} | Kenshi: {{ $ath->nik_kenshi ?: '-' }}
                                    </div>
                                </td>
                                <td>{{ $ath->pivot->dojo_origin ?? '-' }}</td>
                                <td>{{ $ath->pivot->weight ? $ath->pivot->weight . ' kg' : '-' }}</td>
                                <td><span style="font-size:11px;font-weight:700;color:var(--red);">{{ $ath->pivot->rank ?: '-' }}</span></td>
                                <td>
                                    <div style="font-size:11.5px;max-width:200px;">
                                        @php
                                            $matches = $ath->matchNumbers()->wherePivot('registration_id', $activeRegistration->id)->get();
                                        @endphp
                                        @forelse($matches as $m)
                                            <div style="margin-bottom:2px;">• {{ $m->name }}</div>
                                        @empty
                                            <span style="color:var(--red);font-style:italic;">Belum terdaftar</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if($ath->bpjs_status === 'Aktif')
                                        <span style="color:#27ae60;font-weight:600;"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span style="color:var(--red);font-weight:600;"><i class="fa-solid fa-circle-xmark"></i> Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px;">
                                        @if($ath->photo_path)
                                            <a href="{{ asset('storage/' . $ath->photo_path) }}" target="_blank" class="act-btn" title="Foto" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-image"></i></a>
                                        @endif
                                        @if($ath->bpjs_card_path)
                                            <a href="{{ asset('storage/' . $ath->bpjs_card_path) }}" target="_blank" class="act-btn" title="BPJS" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-address-card"></i></a>
                                        @endif
                                        @if($ath->identity_document_path)
                                            <a href="{{ asset('storage/' . $ath->identity_document_path) }}" target="_blank" class="act-btn" title="Identitas" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-id-card"></i></a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button wire:click="openEditAthlete({{ $ath->id }})" class="act-btn view" title="Edit data kenshi ini">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align:center;color:var(--smoke);padding:30px;">Belum ada kenshi yang didaftarkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- SUBMIT DATA CONFIRMATION BUTTON --}}
                @if($activeRegistration->athlete_status !== 'verified')
                    <div style="display:flex;justify-content:flex-end;border-top:1px solid var(--paper);padding-top:14px;">
                        <button wire:click="confirmDataCorrect({{ $activeRegistration->id }})" class="btn-prem success">
                            <i class="fa-solid fa-paper-plane"></i> Konfirmasi Data Atlet Sudah Sesuai
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="ctg-card" style="text-align:center;padding:48px 20px;">
                <i class="fa-solid fa-file-invoice" style="font-size:38px;color:var(--paper2);margin-bottom:14px;display:block;"></i>
                <h4 class="cinzel" style="font-size:15px;margin:0 0 8px;">Belum Ada Transaksi Pendaftaran</h4>
                <p style="font-size:13px;color:var(--smoke);margin:0 0 20px;">Kontingen Anda belum memiliki pendaftaran aktif yang dapat diverifikasi.</p>
                <a href="/piala_walikotasby2026" class="btn-prem primary">Daftar Turnamen Sekarang &rarr;</a>
            </div>
        @endif
    </div>

    {{-- EDIT MODAL FOR ATHLETE --}}
    @if($editingAthleteId)
    <div class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Data Atlet / Kenshi</h3>
                <button wire:click="$set('editingAthleteId', null)" style="background:none;border:none;color:#fff;cursor:pointer;font-size:16px;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group span-2">
                    <label>Nama Lengkap</label>
                    <input type="text" wire:model="editName">
                    @error('editName') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>NIK (16 Digit)</label>
                    <input type="text" wire:model="editNik" maxlength="16">
                    @error('editNik') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>NIK Kenshi (SIM Perkemi)</label>
                    <input type="text" wire:model="editNikKenshi">
                    @error('editNikKenshi') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select wire:model="editGender">
                        <option value="Male">Laki-laki</option>
                        <option value="Female">Perempuan</option>
                    </select>
                    @error('editGender') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Berat Badan (Kg)</label>
                    <input type="number" step="0.1" wire:model="editWeight">
                    @error('editWeight') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Tingkatan / Kyu</label>
                    <input type="text" wire:model="editRank" placeholder="Contoh: Kyu 5, Kyu 1, Dan I">
                    @error('editRank') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Asal Dojo</label>
                    <input type="text" wire:model="editDojo">
                    @error('editDojo') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Nomor BPJS</label>
                    <input type="text" wire:model="editBpjsNumber">
                    @error('editBpjsNumber') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Status BPJS</label>
                    <select wire:model="editBpjsStatus">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                    @error('editBpjsStatus') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                </div>

                {{-- EVENT SELECTIONS --}}
                <div class="form-group span-2" style="margin-top:10px;border-top:1px solid var(--paper);padding-top:10px;">
                    <label style="color:var(--red);font-weight:700;">Kategori Pertandingan yang Diikuti</label>
                    <span style="font-size:11px;color:var(--smoke);margin-bottom:8px;">Pilih kategori pertandingan yang sesuai dengan gender dan kelompok usia.</span>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 1</label>
                    <select wire:model="editEvent1">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 2</label>
                    <select wire:model="editEvent2">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 3</label>
                    <select wire:model="editEvent3">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$set('editingAthleteId', null)" class="btn-prem secondary">Batal</button>
                <button wire:click="saveAthlete" class="btn-prem primary"><i class="fa-solid fa-save"></i> Simpan & Reset Verifikasi</button>
            </div>
        </div>
    </div>
    @endif
</div>
