<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                padding-bottom: 100px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
                font-family: 'Inter', sans-serif;
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            @media(min-width: 768px) {
                .tm-hdr {
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: flex-end;
                }
            }

            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 24px;
                font-weight: 700;
                margin: 0 0 4px;
                color: var(--ink, #2c3e50);
            }

            .tm-hdr p {
                font-size: 13px;
                color: var(--smoke, #7f8c8d);
                margin: 0;
            }

            /* CARDS */
            .tm-card {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
                margin-bottom: 24px;
            }

            .tm-card-hdr {
                background: #fdfbf7;
                border-bottom: 1px solid var(--paper2);
                padding: 16px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                flex-wrap: wrap;
            }

            .tm-card-title {
                font-size: 14px;
                font-weight: 800;
                color: var(--ink);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .tm-card-title i {
                color: var(--red, #c0392b);
                font-size: 16px;
            }

            /* BUTTONS */
            .btn-gen {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 16px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all .2s;
                border: none;
                text-decoration: none;
            }

            .btn-gen.primary {
                background: var(--ink);
                color: #fff;
            }
            .btn-gen.primary:hover {
                background: #1a252f;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(44,62,80,.2);
            }

            .btn-gen.danger {
                background: var(--red, #c0392b);
                color: #fff;
            }
            .btn-gen.danger:hover {
                background: #a93226;
                transform: translateY(-1px);
            }

            .btn-gen.ghost {
                background: #fff;
                color: var(--smoke);
                border: 1px solid var(--paper2);
            }
            .btn-gen.ghost:hover {
                border-color: var(--ink);
                color: var(--ink);
            }

            .btn-gen.warning {
                background: #f39c12;
                color: #fff;
            }
            .btn-gen.warning:hover {
                background: #d68910;
            }

            /* FORMS */
            .tm-select {
                appearance: none;
                background: #fff;
                border: 1px solid var(--paper2);
                color: var(--ink);
                font-size: 13px;
                font-weight: 700;
                border-radius: 12px;
                padding: 10px 36px 10px 16px;
                cursor: pointer;
                outline: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237f8c8d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                background-size: 16px;
            }
            .tm-select:focus {
                border-color: var(--ink);
            }

            /* LISTS */
            .result-list {
                display: flex;
                flex-direction: column;
            }
            .result-item {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 16px 20px;
                border-bottom: 1px solid var(--paper2);
                transition: background .15s;
            }
            .result-item:last-child {
                border-bottom: none;
            }
            .result-item:hover {
                background: #fdfbf7;
            }
            .result-item.tied {
                background: rgba(192, 57, 43, 0.05);
            }
            
            .rank-badge {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 800;
                background: var(--paper2);
                color: var(--smoke);
                flex-shrink: 0;
            }
            .rank-badge.gold { background: #f1c40f; color: #fff; }
            .rank-badge.silver { background: #bdc3c7; color: #fff; }
            .rank-badge.bronze { background: #e67e22; color: #fff; }

            .athlete-info {
                flex: 1;
                min-width: 0;
            }
            .athlete-name {
                font-size: 14px;
                font-weight: 800;
                color: var(--ink);
                text-transform: uppercase;
                margin-bottom: 2px;
            }
            .athlete-contingent {
                font-size: 12px;
                font-weight: 600;
                color: var(--smoke);
            }

            .score-info {
                text-align: right;
                flex-shrink: 0;
            }
            .score-val {
                font-family: 'Outfit', sans-serif;
                font-size: 18px;
                font-weight: 800;
                color: var(--ink);
            }
            .score-lbl {
                font-size: 10px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
            }
            .score-tb {
                font-size: 11px;
                font-weight: 700;
                color: var(--red);
                margin-top: 2px;
            }

            .pool-divider {
                background: var(--ink);
                color: #fff;
                padding: 8px 20px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            /* LAYOUT */
            .tm-layout {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                align-items: start;
            }

            @media(max-width: 1024px) {
                .tm-layout { grid-template-columns: 1fr; }
            }

            /* MODALS */
            .modal-overlay {
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
                z-index: 100; display: flex; align-items: center; justify-content: center;
                padding: 16px;
            }
            .modal-content {
                background: #fff; border-radius: 20px; width: 100%; max-width: 500px;
                overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            }
            .modal-hdr {
                background: #fdfbf7; padding: 20px; border-bottom: 1px solid var(--paper2);
                display: flex; justify-content: space-between; align-items: center;
            }
            .modal-hdr h3 { font-family: 'Cinzel', serif; font-size: 18px; font-weight: 700; color: var(--ink); margin: 0; }
            .modal-body { padding: 24px; }
            
            .form-group { margin-bottom: 16px; }
            .form-group label { display: block; font-size: 11px; font-weight: 700; color: var(--smoke); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.05em; }
            .form-group input, .form-group select { w-full; padding: 10px 14px; border: 1px solid var(--paper2); border-radius: 10px; font-size: 14px; font-weight: 600; color: var(--ink); width: 100%; outline: none; }
            .form-group input:focus, .form-group select:focus { border-color: var(--ink); }
            
            .modal-footer {
                padding: 20px; border-top: 1px solid var(--paper2); display: flex; gap: 12px; justify-content: flex-end;
            }

            .champion-banner {
                background: linear-gradient(135deg, #f1c40f, #f39c12);
                border-radius: 16px;
                padding: 20px;
                color: #fff;
                margin-bottom: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
                box-shadow: 0 10px 30px rgba(243, 156, 18, 0.2);
            }
            .champion-banner-hdr {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .champion-banner-title {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .champion-list {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 12px;
            }
            .champion-item {
                background: rgba(255,255,255,0.15);
                border: 1px solid rgba(255,255,255,0.3);
                border-radius: 12px;
                padding: 12px 16px;
                display: flex;
                align-items: center;
                gap: 16px;
                backdrop-filter: blur(10px);
            }
            .champion-rank { font-size: 24px; }
            .champion-name { font-size: 14px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; }
            .champion-ctg { font-size: 11px; font-weight: 600; opacity: 0.9; }
            .champion-score { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; text-align: right; }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-trophy" style="color:#f39c12;margin-right:8px;"></i>Hasil & Juara Embu</h2>
                <p>Penyisihan · Final · Tanding Ulang · Konfirmasi Juara</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <select wire:model.live="selectedAgeGroupId" class="tm-select">
                    <option value="">Semua Kategori Usia</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="selectedMatchId" class="tm-select" style="min-width: 250px;">
                    <option value="">-- Pilih Nomor Embu --</option>
                    @foreach($embuMatches as $em)
                        <option value="{{ $em->id }}">{{ $em->name }}{{ $em->ageGroup ? ' · '.$em->ageGroup->name : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($selectedMatchId)

            {{-- CHAMPIONS BANNER --}}
            @if($champions->isNotEmpty())
                <div class="champion-banner animate-in fade-in slide-in-from-bottom-4">
                    <div class="champion-banner-hdr">
                        <div class="champion-banner-title">
                            <i class="fas fa-crown"></i> Hasil Akhir Dikonfirmasi
                        </div>
                        <button wire:click="$set('showChampionModal', true)" class="btn-gen ghost" style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); color: #fff;">
                            <i class="fas fa-redo"></i> Update
                        </button>
                    </div>
                    <div class="champion-list">
                        @foreach($champions->take(4) as $champ)
                            @php
                                $rankIcon = match($champ->rank) { 1 => '🥇', 2 => '🥈', 3 => '🥉', 4 => '🥉', default => '#'.$champ->rank };
                                $athletes = $champ->matchNumber?->athletes?->filter(fn($a) => $a->pivot->registration_id == $champ->registration_id) ?? collect();
                            @endphp
                            <div class="champion-item">
                                <div class="champion-rank">{{ $rankIcon }}</div>
                                <div style="flex:1; min-width:0;">
                                    <div class="champion-ctg">{{ $champ->registration?->contingent?->name }}</div>
                                </div>
                                <div>
                                    <div class="champion-score">{{ number_format($champ->accumulated_score, 1) }}</div>
                                    <div style="font-size:10px; font-weight:700; opacity:0.8; text-transform:uppercase;">Akumulasi</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($champions->count() > 4)
                        <div style="font-size:12px; font-weight:700; text-align:center; opacity:0.8;">
                            +{{ $champions->count() - 4 }} peserta lainnya
                        </div>
                    @endif
                </div>
            @endif

            <div class="tm-layout">
                {{-- PENYISIHAN --}}
                <div class="tm-card">
                    <div class="tm-card-hdr">
                        <div class="tm-card-title">
                            <i class="fas fa-list-ol" style="color: var(--ink);"></i>
                            Penyisihan
                        </div>
                        <div style="display:flex; gap:8px;">
                            @if(!empty($tiedPenyisihanIds))
                                <button wire:click="openTiebreakModal('Penyisihan', {{ json_encode($tiedPenyisihanIds) }})" class="btn-gen danger">
                                    <i class="fas fa-equals"></i> Tanding Ulang ({{ count($tiedPenyisihanIds) }})
                                </button>
                            @endif
                            @if(!$finalExists)
                                <button wire:click="openGenerateFinalModal" class="btn-gen warning">
                                    <i class="fas fa-arrow-right"></i> Generate Final
                                </button>
                            @else
                                <button wire:click="openGenerateFinalModal" class="btn-gen ghost">
                                    <i class="fas fa-sync"></i> Re-generate
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="result-list">
                        @forelse($penyisihanRanking as $poolId => $poolParticipants)
                            @php
                                $poolCount = $penyisihanRanking->count();
                                $finalQuotaPerPool = $poolCount === 1 ? 999 : ($poolCount === 2 ? 4 : ($poolCount === 3 ? 3 : 2));
                                $quotaText = $poolCount === 1 ? 'Semua Lolos' : "Top {$finalQuotaPerPool} Lolos";
                            @endphp
                            <div class="pool-divider">
                                <span>{{ $poolParticipants->first()['pool_name'] ?? 'Pool' }}</span>
                                <span style="font-size:10px; background:rgba(255,255,255,0.2); padding:2px 6px; border-radius:4px;">{{ $quotaText }}</span>
                            </div>
                            
                            @foreach($poolParticipants as $idx => $reg)
                                @php
                                    $score = $reg['effective_score'];
                                    $isTied = in_array($reg['id'], $tiedPenyisihanIds);
                                    $qualifies = $idx < $finalQuotaPerPool;
                                    
                                    $badgeClass = '';
                                    if($idx === 0) $badgeClass = 'gold';
                                    elseif($idx === 1) $badgeClass = 'silver';
                                    elseif($idx === 2) $badgeClass = 'bronze';
                                @endphp
                                <div class="result-item {{ $isTied ? 'tied' : '' }}" style="{{ !$qualifies ? 'opacity:0.5;' : '' }}">
                                    <div class="rank-badge {{ $badgeClass }}">{{ $idx + 1 }}</div>
                                    <div class="athlete-info">
                                        @foreach($reg['athletes'] as $ath)
                                            <div class="athlete-name">{{ $ath->name }}</div>
                                        @endforeach
                                        <div class="athlete-contingent">{{ $reg['contingent']?->name }}</div>
                                    </div>
                                    <div class="score-info" style="display:flex; align-items:center; gap:12px;">
                                        @if($isTied)
                                            <span style="font-size:10px; font-weight:800; background:var(--red); color:#fff; padding:2px 6px; border-radius:4px;">SERI</span>
                                        @endif
                                        
                                        @if($score)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                                <div class="score-val">{{ number_format($score->nilai_akhir, 1) }}</div>
                                                
                                                @php
                                                    $rawVals = [
                                                        1 => (float)$score->judge_1,
                                                        2 => (float)$score->judge_2,
                                                        3 => (float)$score->judge_3,
                                                        4 => (float)$score->judge_4,
                                                        5 => (float)$score->judge_5,
                                                    ];
                                                    
                                                    // Only calculate dropped scores if ALL 5 judges have scored
                                                    $scoredJudges = array_filter($rawVals, fn($v) => $v > 0);
                                                    $minKey = null;
                                                    $maxKey = null;
                                                    
                                                    if (count($scoredJudges) === 5) {
                                                        $sortedVals = $rawVals;
                                                        asort($sortedVals);
                                                        $keys = array_keys($sortedVals);
                                                        $minKey = $keys[0];
                                                        $maxKey = $keys[4];
                                                    }
                                                @endphp
                                                
                                                <div style="display: flex; gap: 8px; margin-top: 4px; padding-top: 4px; border-top: 1px dashed var(--paper2);">
                                                    @for($i=1; $i<=5; $i++)
                                                        @php
                                                            $val = $score->{'judge_'.$i};
                                                            $isDropped = ($i === $minKey || $i === $maxKey);
                                                        @endphp
                                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                                            <span style="font-size: 9px; font-weight: 800; color: var(--smoke); margin-bottom: 2px;">W{{ $i }}</span>
                                                            <span style="font-size: 11px; font-weight: 800; font-family: 'Outfit', sans-serif; {{ $isDropped ? 'text-decoration: line-through; color: var(--smoke); opacity: 0.6;' : 'color: var(--ink);' }}">
                                                                {{ $val > 0 ? number_format($val, 1) : '-' }}
                                                            </span>
                                                        </div>
                                                    @endfor
                                                </div>

                                                @if($reg['tiebreak_score'])
                                                    <div class="score-tb">TB: {{ number_format($reg['tiebreak_score']->nilai_akhir, 1) }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <div style="font-size:14px; color:var(--smoke); font-style:italic;">-</div>
                                        @endif
                                        
                                        @if($qualifies && $finalExists)
                                            <i class="fas fa-check-circle" style="color:#27ae60; font-size:16px;"></i>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @empty
                            <div style="padding: 40px; text-align: center; color: var(--smoke);">
                                <i class="fas fa-folder-open" style="font-size:32px; opacity:0.3; margin-bottom:12px;"></i>
                                <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Belum ada nilai Penyisihan</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- FINAL --}}
                <div class="tm-card">
                    <div class="tm-card-hdr">
                        <div class="tm-card-title">
                            <i class="fas fa-trophy" style="color: #f39c12;"></i>
                            Final
                        </div>
                        <div style="display:flex; gap:8px;">
                            @if(!empty($tiedFinalIds))
                                <button wire:click="openTiebreakModal('Final', {{ json_encode($tiedFinalIds) }})" class="btn-gen danger">
                                    <i class="fas fa-equals"></i> Tanding Ulang
                                </button>
                            @endif
                            @if($finalExists)
                                <button wire:click="$set('showChampionModal', true)" class="btn-gen warning">
                                    <i class="fas fa-crown"></i> Konfirmasi Juara
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$finalExists)
                        <div style="padding: 60px 40px; text-align: center; color: var(--smoke);">
                            <i class="fas fa-lock" style="font-size:32px; opacity:0.3; margin-bottom:12px;"></i>
                            <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Babak Final Belum Dibuka</div>
                            <div style="font-size:12px; margin-top:4px;">Generate Final dari panel Penyisihan</div>
                        </div>
                    @else
                        <div class="result-list">
                            @forelse($finalRanking as $idx => $reg)
                                @php
                                    $isTiedFinal = in_array($reg['id'], $tiedFinalIds);
                                    
                                    $badgeClass = '';
                                    if($idx === 0) $badgeClass = 'gold';
                                    elseif($idx === 1) $badgeClass = 'silver';
                                    elseif($idx === 2) $badgeClass = 'bronze';
                                @endphp
                                <div class="result-item {{ $isTiedFinal ? 'tied' : '' }}">
                                    <div class="rank-badge {{ $badgeClass }}">{{ $idx + 1 }}</div>
                                    <div class="athlete-info">
                                        @foreach($reg['athletes'] as $ath)
                                            <div class="athlete-name">{{ $ath->name }}</div>
                                        @endforeach
                                        <div class="athlete-contingent">{{ $reg['contingent']?->name }}</div>
                                    </div>
                                    <div class="score-info">
                                        @if($isTiedFinal)
                                            <span style="display:inline-block; margin-bottom:4px; font-size:10px; font-weight:800; background:var(--red); color:#fff; padding:2px 6px; border-radius:4px;">SERI</span>
                                        @endif
                                        <div style="font-size:11px; font-weight:700; color:var(--smoke); margin-bottom:2px;">
                                            P: {{ number_format($reg['penyisihan_score']?->nilai_akhir ?? 0, 1) }} + F: {{ $reg['final_score'] ? number_format($reg['final_score']->nilai_akhir, 1) : '–' }}
                                        </div>
                                        @if($reg['accumulated'] > 0 || $reg['final_scored'] ?? false)
                                            <div class="score-val" style="color:#f39c12;">{{ number_format($reg['accumulated'], 1) }}</div>
                                            @if(!($reg['final_scored'] ?? false))
                                                <div style="font-size:10px; font-style:italic; color:var(--smoke);">Final belum dinilai</div>
                                            @endif
                                        @else
                                            <div style="font-size:13px; font-style:italic; color:var(--smoke);">Belum dinilai</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 40px; text-align: center; color: var(--smoke);">
                                    <i class="fas fa-trophy" style="font-size:32px; opacity:0.3; margin-bottom:12px;"></i>
                                    <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Belum ada peserta final</div>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            {{-- MODALS --}}
            
            {{-- GENERATE FINAL MODAL --}}
            @if($showGenerateFinalModal)
                <div class="modal-overlay" wire:click.self="$set('showGenerateFinalModal', false)">
                    <div class="modal-content animate-in zoom-in-95 duration-200">
                        <div class="modal-hdr">
                            <h3><i class="fas fa-arrow-right" style="color:#f39c12; margin-right:8px;"></i> Generate Final</h3>
                            <button wire:click="$set('showGenerateFinalModal', false)" style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--smoke);"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div class="form-group">
                                    <label>Court</label>
                                    <select wire:model="finalCourtId">
                                        <option value="">– Pilih –</option>
                                        @foreach($courts as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Pool</label>
                                    <select wire:model="finalPoolId">
                                        <option value="">– Pilih –</option>
                                        @foreach($pools as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sesi</label>
                                    <select wire:model="finalSessionTimeId">
                                        <option value="">– Pilih –</option>
                                        @foreach($sessionTimes as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Rundown</label>
                                    <select wire:model="finalRundownId">
                                        <option value="">– Pilih –</option>
                                        @foreach($rundowns as $r) <option value="{{ $r->id }}">{{ $r->name }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Tanggal Jadwal</label>
                                <input type="date" wire:model="finalScheduleDate">
                            </div>

                            @if(!empty($tiedPenyisihanIds))
                                <div style="margin-top:20px; padding:12px 16px; background:rgba(192,57,43,0.1); border:1px solid rgba(192,57,43,0.2); border-radius:12px;">
                                    <div style="font-size:12px; font-weight:800; color:var(--red); text-transform:uppercase; margin-bottom:4px;"><i class="fas fa-exclamation-triangle"></i> Ada nilai seri di batas kuota!</div>
                                    <div style="font-size:12px; color:var(--red); opacity:0.9;">{{ count($tiedPenyisihanIds) }} peserta perlu tanding ulang sebelum generate final.</div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$set('showGenerateFinalModal', false)" class="btn-gen ghost">Batal</button>
                            <button wire:click="generateFinal" wire:loading.attr="disabled" class="btn-gen warning">
                                <span wire:loading.remove wire:target="generateFinal"><i class="fas fa-check"></i> Generate Final</span>
                                <span wire:loading wire:target="generateFinal"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TIEBREAK MODAL --}}
            @if($showTiebreakModal)
                <div class="modal-overlay" wire:click.self="$set('showTiebreakModal', false)">
                    <div class="modal-content animate-in zoom-in-95 duration-200">
                        <div class="modal-hdr">
                            <h3><i class="fas fa-redo" style="color:var(--red); margin-right:8px;"></i> Jadwal Tanding Ulang</h3>
                            <button wire:click="$set('showTiebreakModal', false)" style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--smoke);"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div style="font-size:13px; font-weight:700; color:var(--ink); margin-bottom:16px;">
                                Babak: {{ $tiebreakRound }} — {{ count($tiebreakRegistrationIds) }} Peserta Seri
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-group">
                                    <label>Court</label>
                                    <select wire:model="tiebreakCourtId">
                                        <option value="">– Pilih –</option>
                                        @foreach($courts as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sesi</label>
                                    <select wire:model="tiebreakSessionTimeId">
                                        <option value="">– Pilih –</option>
                                        @foreach($sessionTimes as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Rundown</label>
                                    <select wire:model="tiebreakRundownId">
                                        <option value="">– Pilih –</option>
                                        @foreach($rundowns as $r) <option value="{{ $r->id }}">{{ $r->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" wire:model="tiebreakScheduleDate">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$set('showTiebreakModal', false)" class="btn-gen ghost">Batal</button>
                            <button wire:click="generateTiebreakSchedule" wire:loading.attr="disabled" class="btn-gen danger">
                                <span wire:loading.remove wire:target="generateTiebreakSchedule"><i class="fas fa-calendar-plus"></i> Buat Jadwal</span>
                                <span wire:loading wire:target="generateTiebreakSchedule"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- CONFIRM CHAMPION MODAL --}}
            @if($showChampionModal)
                <div class="modal-overlay" wire:click.self="$set('showChampionModal', false)">
                    <div class="modal-content animate-in zoom-in-95 duration-200">
                        <div class="modal-hdr">
                            <h3><i class="fas fa-crown" style="color:#f39c12; margin-right:8px;"></i> Konfirmasi Hasil Akhir</h3>
                            <button wire:click="$set('showChampionModal', false)" style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--smoke);"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div style="background:#fdfbf7; border:1px solid #f1c40f; border-radius:12px; padding:12px 16px; margin-bottom:16px;">
                                <div style="font-size:12px; font-weight:800; color:#d68910; text-transform:uppercase; margin-bottom:4px;"><i class="fas fa-info-circle"></i> Aksi ini akan menyimpan data juara</div>
                                <div style="font-size:12px; color:#d68910;">Data juara sebelumnya (jika ada) akan diganti dengan hasil terbaru.</div>
                            </div>

                            @if(!empty($tiedFinalIds))
                                <div style="margin-bottom:16px; padding:12px 16px; background:rgba(192,57,43,0.1); border:1px solid rgba(192,57,43,0.2); border-radius:12px;">
                                    <div style="font-size:12px; font-weight:800; color:var(--red); text-transform:uppercase; margin-bottom:4px;"><i class="fas fa-exclamation-triangle"></i> Masih ada nilai seri!</div>
                                    <div style="font-size:12px; color:var(--red); opacity:0.9;">{{ count($tiedFinalIds) }} peserta memiliki nilai akumulasi sama. Selesaikan tanding ulang terlebih dahulu.</div>
                                </div>
                            @endif

                            <div style="max-height:250px; overflow-y:auto; border:1px solid var(--paper2); border-radius:12px; background:#faf9f6;">
                                @foreach($finalRanking->take(4) as $idx => $reg)
                                    @php
                                        $displayRank = match($idx) { 
                                            0 => '🥇', 
                                            1 => '🥈', 
                                            2 => '🥉', 
                                            3 => '🥉', 
                                            default => '' 
                                        };
                                        $label = match($idx) {
                                            0 => 'Juara 1',
                                            1 => 'Juara 2',
                                            2 => 'Juara 3 Bersama',
                                            3 => 'Juara 3 Bersama',
                                            default => ''
                                        };
                                    @endphp
                                    <div style="padding:12px 16px; border-bottom:1px solid var(--paper2); display:flex; align-items:center; gap:12px;">
                                        <div style="text-align:center; min-width:60px;">
                                            <div style="font-size:24px;">{{ $displayRank }}</div>
                                            <div style="font-size:9px; font-weight:800; color:var(--smoke); text-transform:uppercase; margin-top:2px;">{{ $label }}</div>
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            @foreach($reg['athletes'] as $ath)
                                                <div style="font-size:13px; font-weight:800; color:var(--ink); text-transform:uppercase;">{{ $ath->name }}</div>
                                            @endforeach
                                        </div>
                                        <div style="font-family:'Outfit',sans-serif; font-size:16px; font-weight:800; color:#f39c12;">
                                            {{ number_format($reg['accumulated'] ?? 0, 1) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$set('showChampionModal', false)" class="btn-gen ghost">Batal</button>
                            <button wire:click="confirmChampion" wire:loading.attr="disabled" class="btn-gen warning">
                                <span wire:loading.remove wire:target="confirmChampion"><i class="fas fa-crown"></i> Konfirmasi Juara</span>
                                <span wire:loading wire:target="confirmChampion"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div style="background:#fff; border:2px dashed var(--paper2); border-radius:24px; padding:60px 20px; text-align:center; margin-top:24px;">
                <div style="width:64px; height:64px; background:#fdfbf7; border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                    <i class="fas fa-trophy" style="font-size:28px; color:#f39c12; opacity:0.5;"></i>
                </div>
                <div style="font-size:14px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Pilih Nomor Embu</div>
                <div style="font-size:13px; color:var(--smoke);">Gunakan dropdown di atas untuk melihat hasil dan peringkat</div>
            </div>
        @endif
    </div>
</div>
