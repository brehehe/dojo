<div wire:poll.2s="loadActiveMatch" class="ref-scoring-shell">
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       REFEREE SCORING DASHBOARD — Mobile Premium
    ══════════════════════════════════════════════════════ */

    body.referee-scoring-immersive {
        overflow: auto;
    }
    body.referee-scoring-immersive .premium-header,
    body.referee-scoring-immersive .mob-bottomnav {
        display: none !important;
    }
    body.referee-scoring-immersive main.premium-main {
        height: 100dvh;
        min-height: 100dvh;
        padding-bottom: 0 !important;
        overflow: hidden;
    }
    body.referee-scoring-immersive .ref-scoring-shell {
        height: 100dvh;
        min-height: 100dvh;
        padding-bottom: 32px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior-y: contain;
        touch-action: pan-y;
    }
    .ref-scoring-shell {
        position: relative;
    }
    .ref-fullscreen-btn {
        position: fixed;
        top: 14px;
        right: 14px;
        z-index: 80;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 999px;
        background: rgba(15, 13, 11, .92);
        color: #fff;
        padding: 12px 16px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .02em;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
        cursor: pointer;
    }
    .ref-fullscreen-btn i {
        font-size: 14px;
    }
    /* ── STATUS BAR ── */
    .ref-statusbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 18px 10px; gap: 10px; flex-wrap: wrap;
    }
    .ref-judge-info { display: flex; align-items: center; gap: 10px; }
    .ref-judge-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: rgba(192,57,43,.1); color: var(--red);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
        flex-shrink: 0;
    }
    .ref-judge-name { font-size: 19px; font-weight: 700; color: var(--ink); margin: 0 0 1px; }
    .ref-judge-sub  { font-size: 14px; color: var(--smoke); margin: 0; }

    .ref-judge-badge {
        padding: 6px 13px; background: var(--ink); color: var(--gold-lt);
        border-radius: 20px; font-size: 14px; font-weight: 700;
        font-family: 'Cinzel', serif; letter-spacing: .05em; white-space: nowrap;
    }

    /* ── SYNC INDICATOR ── */
    .ref-sync {
        display: flex; align-items: center; justify-content: center;
        gap: 6px; padding: 8px 0 12px;
        font-size: 13px; color: var(--smoke); text-transform: uppercase; letter-spacing: .1em;
    }
    .ref-sync i { color: #27ae60; }

    /* ── WARNING BANNER ── */
    .ref-warning {
        margin: 0 16px 12px; padding: 12px 14px;
        background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.25);
        border-radius: 12px; display: flex; gap: 12px; align-items: flex-start;
    }
    .ref-warning i { color: #d97706; font-size: 16px; margin-top: 2px; flex-shrink: 0; }
    .ref-warning-title { font-size: 18px; font-weight: 700; color: #92400e; margin: 0 0 2px; }
    .ref-warning-text  { font-size: 15px; color: #78350f; margin: 0; line-height: 1.5; }

    /* ── MATCH HEADER ── */
    .ref-match-hdr {
        background: var(--ink); margin: 0 16px 12px;
        border-radius: 16px; padding: 16px 18px 14px;
        position: relative; overflow: hidden;
    }
    .ref-match-hdr::after {
        content: ''; position: absolute; right: -20px; bottom: -20px;
        width: 100px; height: 100px;
        background: rgba(192,57,43,.15); border-radius: 50%;
    }
    .ref-live-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; background: var(--red); color: #fff;
        border-radius: 6px; font-size: 12px; font-weight: 700;
        letter-spacing: .1em; margin-bottom: 8px;
    }
    .ref-live-dot {
        width: 6px; height: 6px; border-radius: 50%; background: #fff;
        animation: blink .8s ease-in-out infinite alternate;
    }
    @keyframes blink { from { opacity: 1; } to { opacity: .3; } }
    .ref-match-type {
        font-size: 12.5px; color: var(--smoke); text-transform: uppercase;
        letter-spacing: .15em; margin-bottom: 4px;
    }
    .ref-match-name {
        font-family: 'Cinzel', serif; font-size: 27px; font-weight: 700;
        color: #fff; margin: 0 0 2px;
    }
    .ref-match-sub { font-size: 15px; color: rgba(255,255,255,.4); margin: 0; }

    /* ── MATCH INFO CHIPS (kontingen, court, pool, teknik) ── */
    .ref-info-chips {
        padding: 0 16px 12px;
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    }
    .ref-info-chip {
        background: #fff; border: 1px solid var(--paper2);
        border-radius: 12px; padding: 10px 12px;
        display: flex; align-items: flex-start; gap: 9px;
    }
    .ref-info-chip-icon {
        width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 12px;
    }
    .ref-info-chip-icon.red   { background: rgba(192,57,43,.1);  color: var(--red); }
    .ref-info-chip-icon.blue  { background: rgba(52,152,219,.1); color: #2980b9; }
    .ref-info-chip-icon.green { background: rgba(39,174,96,.1);  color: #27ae60; }
    .ref-info-chip-icon.gold  { background: rgba(212,168,67,.12); color: #b8860b; }
    .ref-info-chip-body { min-width: 0; }
    .ref-info-chip-label {
        font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; color: var(--smoke); margin: 0 0 2px;
    }
    .ref-info-chip-value {
        font-size: 18px; font-weight: 700; color: var(--ink);
        margin: 0; white-space: normal; overflow-wrap: anywhere; word-break: break-word;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── TECHNIQUE PANEL ── */
    .ref-technique-panel {
        margin: 0 16px 12px;
        background: #fff;
        border: 1px solid var(--paper2);
        border-radius: 14px;
        padding: 14px 16px;
    }
    .ref-technique-hdr {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 10px;
    }
    .ref-technique-icon {
        width: 34px; height: 34px; border-radius: 10px;
        background: rgba(212,168,67,.12); color: #b8860b;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; flex-shrink: 0;
    }
    .ref-technique-label {
        font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .12em; color: var(--smoke); margin: 0 0 2px;
    }
    .ref-technique-title {
        font-size: 19px; font-weight: 700; color: var(--ink); margin: 0;
    }
    .ref-technique-text {
        font-size: 18px; line-height: 1.7; color: var(--ink);
        margin: 0;
    }
    .ref-technique-list {
        margin: 0;
        padding-left: 18px;
        color: var(--ink);
        list-style: decimal;
        list-style-position: outside;
    }
    .ref-technique-list li {
        font-size: 18px;
        line-height: 1.7;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /* ── WAIT STATE ── */
    .ref-wait {
        margin: 0 16px 16px; padding: 32px 24px; text-align: center;
        background: #fff; border-radius: 16px; border: 1px solid var(--paper2);
    }
    .ref-wait-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: var(--paper); margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; color: var(--smoke);
        animation: pulse-slow 2.5s ease-in-out infinite;
    }
    @keyframes pulse-slow { 0%,100% { opacity: 1; } 50% { opacity: .4; } }
    .ref-wait h3 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 6px; }
    .ref-wait p  { font-size: 15px; color: var(--smoke); margin: 0; line-height: 1.6; max-width: 260px; margin-inline: auto; }

    /* ── SCORE FORM PANEL ── */
    .ref-form-panel { padding: 0 16px 16px; }

    .ref-score-group {
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
        margin-bottom: 12px; overflow: hidden;
    }
    .ref-score-group-hdr {
        padding: 10px 14px; background: var(--paper);
        border-bottom: 1px solid var(--paper2);
        font-size: 13px; font-weight: 700; color: var(--smoke);
        text-transform: uppercase; letter-spacing: .12em;
        display: flex; align-items: center; gap: 7px;
    }
    .ref-score-group-hdr i { font-size: 11px; }
    .ref-score-items { padding: 10px 14px; display: flex; flex-direction: column; gap: 10px; }
    .ref-score-row { display: flex; align-items: center; gap: 10px; }
    .ref-score-label { flex: 1; font-size: 23px; font-weight: 700; color: var(--ink); }
    .ref-score-desc  { font-size: 18px; color: var(--smoke); margin-top: 4px; line-height: 1.45; }

    /* ── SCORE INPUT (range 8–10) ── */
    .ref-score-input-wrap { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex: 0 0 148px; }
    .ref-score-input {
        width: 148px; padding: 16px 10px; border: 1.5px solid var(--paper2);
        border-radius: 10px; font-family: 'Cinzel', serif; font-size: 38px;
        font-weight: 700; text-align: center; color: var(--ink);
        background: var(--paper); outline: none; transition: border .15s, box-shadow .15s;
        -moz-appearance: textfield;
    }
    .ref-score-input::-webkit-inner-spin-button,
    .ref-score-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .ref-score-input:focus { border-color: var(--red); background: #fff; box-shadow: 0 0 0 3px rgba(192,57,43,.1); }
    .ref-score-input.is-valid   { border-color: #27ae60; }
    .ref-score-input.is-invalid { border-color: var(--red); }
    .ref-score-range-hint { font-size: 13px; color: var(--smoke); letter-spacing: .05em; }

    /* ── TOTAL BANNER ── */
    .ref-total-banner {
        background: var(--ink); border-radius: 14px; margin-bottom: 12px;
        padding: 16px 18px; display: flex; align-items: center; justify-content: space-between;
    }
    .ref-total-left {}
    .ref-total-label { font-size: 14px; color: var(--smoke); text-transform: uppercase; letter-spacing: .1em; }
    .ref-total-sub   { font-size: 13px; color: rgba(255,255,255,.3); font-style: italic; margin-top: 2px; }
    .ref-total-val {
        font-family: 'Cinzel', serif; font-size: 54px; font-weight: 700;
        color: var(--gold-lt); line-height: 1;
    }
    .ref-avg-val {
        font-size: 15px; color: var(--smoke); text-align: right; margin-top: 4px;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── NOTES ── */
    .ref-notes-wrap {
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
        margin-bottom: 14px; overflow: hidden;
    }
    .ref-notes-hdr {
        padding: 10px 14px; border-bottom: 1px solid var(--paper2);
        font-size: 13px; font-weight: 700; color: var(--smoke);
        text-transform: uppercase; letter-spacing: .12em; background: var(--paper);
        display: flex; align-items: center; gap: 6px;
    }
    .ref-notes-textarea {
        width: 100%; border: none; outline: none; padding: 12px 14px;
        font-family: 'DM Sans', sans-serif; font-size: 18px; color: var(--ink);
        background: transparent; resize: none; min-height: 80px; box-sizing: border-box;
    }

    /* ── ACTION BUTTONS ── */
    .ref-actions { display: grid; grid-template-columns: 1fr 2fr; gap: 10px; }
    .ref-btn-reset {
        padding: 14px; background: var(--paper); border: 1px solid var(--paper2);
        border-radius: 12px; font-size: 16px; font-weight: 700; color: var(--ink);
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        transition: all .15s;
    }
    .ref-btn-reset:hover { background: var(--paper2); }
    .ref-btn-submit {
        padding: 14px; background: var(--red); border: none;
        border-radius: 12px; font-size: 16px; font-weight: 700; color: #fff;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(192,57,43,.35); transition: all .2s;
    }
    .ref-btn-submit:hover { background: var(--red-deep); }
    .ref-btn-submit:active { transform: scale(.98); }

    /* ── RANDORI INFO ── */
    .ref-randori-info {
        margin: 0 16px 16px; padding: 24px; text-align: center;
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
    }
    .ref-randori-info i { font-size: 28px; color: #2980b9; margin-bottom: 12px; display: block; }
    .ref-randori-info h3 { font-family: 'Cinzel', serif; font-size: 19px; font-weight: 700; color: var(--ink); margin: 0 0 6px; }
    .ref-randori-info p  { font-size: 15px; color: var(--smoke); margin: 0; line-height: 1.6; max-width: 280px; margin-inline: auto; }

    @media (max-width: 640px) {
        .ref-statusbar {
            padding: 18px 14px 10px;
        }
        .ref-fullscreen-btn {
            top: 10px;
            right: 10px;
            padding: 11px 14px;
            font-size: 13px;
        }
        .ref-warning,
        .ref-match-hdr,
        .ref-technique-panel,
        .ref-randori-info,
        .ref-wait {
            margin-inline: 12px;
        }
        .ref-info-chips,
        .ref-form-panel {
            padding-inline: 12px;
        }
        .ref-info-chips {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .ref-match-hdr {
            padding: 14px 14px 12px;
        }
        .ref-match-name {
            font-size: 23px;
            line-height: 1.35;
            max-width: calc(100% - 28px);
        }
        .ref-technique-panel {
            padding: 12px 14px;
        }
        .ref-technique-list {
            padding-left: 20px;
        }
        .ref-score-group-hdr,
        .ref-notes-hdr {
            line-height: 1.4;
        }
        .ref-score-row {
            align-items: center;
            flex-direction: row;
            gap: 12px;
        }
        .ref-score-input-wrap {
            width: 128px;
            flex-basis: 128px;
            align-items: flex-end;
        }
        .ref-score-input {
            width: 128px;
            min-height: 62px;
            font-size: 32px;
        }
        .ref-score-range-hint {
            text-align: right;
        }
        .ref-total-banner {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
        }
        .ref-total-val,
        .ref-avg-val {
            text-align: left;
        }
        .ref-actions {
            grid-template-columns: 1fr;
        }
        .ref-btn-reset,
        .ref-btn-submit {
            min-height: 48px;
        }
        .ref-randori-info,
        .ref-wait {
            padding: 20px 16px;
        }
    }

    @media (min-width: 641px) and (max-width: 960px) {
        .ref-technique-panel {
            padding: 14px 15px;
        }
        .ref-score-input {
            width: 136px;
        }
    }
    </style>
    @endpush
    @push('scripts')
    <script>
    (() => {
        const bodyClass = 'referee-scoring-immersive';

        const applyImmersiveMode = () => {
            document.body.classList.add(bodyClass);
        };

        const cleanupImmersiveMode = () => {
            document.body.classList.remove(bodyClass);
        };

        const clearZeroOnFocus = (input) => {
            if (!input) {
                return;
            }

            if (input.value === '0' || input.value === '0.0') {
                input.value = '';
            }

            input.select?.();
        };

        const restoreZeroOnBlur = (input) => {
            if (!input) {
                return;
            }

            if (input.value !== '') {
                return;
            }

            input.value = '0';
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        };

        const syncFullscreenButton = () => {
            document.querySelectorAll('[data-ref-fullscreen-btn]').forEach((button) => {
                const icon = button.querySelector('i');
                const label = button.querySelector('[data-ref-fullscreen-label]');
                const isFullscreen = !!document.fullscreenElement;

                if (icon) {
                    icon.className = isFullscreen
                        ? 'fa-solid fa-compress'
                        : 'fa-solid fa-expand';
                }

                if (label) {
                    label.textContent = isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen';
                }
            });
        };

        const attemptFullscreen = async () => {
            const element = document.documentElement;

            if (document.fullscreenElement || !element?.requestFullscreen) {
                syncFullscreenButton();
                return;
            }

            try {
                await element.requestFullscreen();
            } catch (_error) {
                // Ignored: most browsers block fullscreen without a user gesture.
            }

            syncFullscreenButton();
        };

        const exitFullscreen = async () => {
            if (!document.fullscreenElement || !document.exitFullscreen) {
                syncFullscreenButton();
                return;
            }

            try {
                await document.exitFullscreen();
            } catch (_error) {
                // Ignore browser exit failures and keep UI state synced.
            }

            syncFullscreenButton();
        };

        const toggleFullscreen = async () => {
            if (document.fullscreenElement) {
                await exitFullscreen();

                return;
            }

            await attemptFullscreen();
        };

        const initRefereeScoringView = () => {
            applyImmersiveMode();
            attemptFullscreen();
            syncFullscreenButton();
        };

        window.refereeScoringFullscreen = toggleFullscreen;
        window.refereeScoreInputFocus = clearZeroOnFocus;
        window.refereeScoreInputBlur = restoreZeroOnBlur;

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initRefereeScoringView, { once: true });
        } else {
            initRefereeScoringView();
        }

        document.addEventListener('livewire:navigated', initRefereeScoringView);
        document.addEventListener('livewire:navigating', cleanupImmersiveMode);
        document.addEventListener('fullscreenchange', syncFullscreenButton);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                applyImmersiveMode();
            }
        });

        window.addEventListener('pagehide', cleanupImmersiveMode);
        window.addEventListener('beforeunload', cleanupImmersiveMode);

        // Retry after first user interaction for browsers that require a gesture.
        window.addEventListener('pointerdown', attemptFullscreen, { once: true });
    })();
    </script>
    @endpush

    <button type="button" class="ref-fullscreen-btn" data-ref-fullscreen-btn onclick="window.refereeScoringFullscreen?.()">
        <i class="fa-solid fa-expand"></i>
        <span data-ref-fullscreen-label>Fullscreen</span>
    </button>

    {{-- ── STATUS BAR ── --}}
    <div class="ref-statusbar">
        <div class="ref-judge-info">
            <div class="ref-judge-icon"><i class="fa-solid fa-gavel"></i></div>
            <div>
                <p class="ref-judge-name">{{ $referee?->user?->name ?? auth()->user()?->name ?? 'Wasit' }}</p>
                <p class="ref-judge-sub">Wasit Juri Aktif</p>
            </div>
        </div>
        @if($judgeIndex)
            <div class="ref-judge-badge">{{ $this->judgeLabel }}</div>
        @endif
    </div>

    {{-- ── SYNC BADGE ── --}}
    <div class="ref-sync">
        <i class="fa-solid fa-satellite-dish fa-spin-pulse"></i>
        Sinkronisasi Otomatis Aktif
    </div>

    {{-- ── WARNING: bukan wasit terdaftar ── --}}
    @if(!$referee)
        <div class="ref-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>
                <p class="ref-warning-title">Akun Bukan Wasit Terdaftar</p>
                <p class="ref-warning-text">Akun Anda tidak terdaftar sebagai wasit. Scoring tidak dapat dikirim. Pastikan login menggunakan akun wasit yang benar.</p>
            </div>
        </div>
    @endif

    {{-- ── MATCH ACTIVE ── --}}
    @if($activeMatch && $this->checkParticipantCalled())

        {{-- Match Header --}}
        <div class="ref-match-hdr">
            <div class="ref-live-badge"><span class="ref-live-dot"></span> LIVE ON COURT</div>
            <p class="ref-match-type">{{ strtoupper($activeMatch->draft_type) }}</p>
            <h3 class="ref-match-name">{{ $activeMatch->name }}</h3>
            <p class="ref-match-sub">Berikan penilaian terbaik Anda secara objektif.</p>
        </div>

        {{-- ── INFO CHIPS: Kontingen, Court, Pool, Teknik ── --}}
        @php
            $courtName   = $assignedCourt?->name ?? '-';
            $poolName    = $activeMatch->ageGroup?->name ?? ($activeMatch->pool?->name ?? '-');
        @endphp
        <div class="ref-info-chips">
            <div class="ref-info-chip">
                <div class="ref-info-chip-icon red"><i class="fa-solid fa-flag"></i></div>
                <div class="ref-info-chip-body">
                    <p class="ref-info-chip-label">Kontingen</p>
                    <p class="ref-info-chip-value" title="{{ $activeContingentName }}">{{ $activeContingentName }}</p>
                </div>
            </div>
            <div class="ref-info-chip">
                <div class="ref-info-chip-icon blue"><i class="fa-solid fa-layer-group"></i></div>
                <div class="ref-info-chip-body">
                    <p class="ref-info-chip-label">Court / Lapangan</p>
                    <p class="ref-info-chip-value">{{ $courtName }}</p>
                </div>
            </div>
            <div class="ref-info-chip">
                <div class="ref-info-chip-icon green"><i class="fa-solid fa-sitemap"></i></div>
                <div class="ref-info-chip-body">
                    <p class="ref-info-chip-label">Pool / Kelas</p>
                    <p class="ref-info-chip-value" title="{{ $poolName }}">{{ $poolName }}</p>
                </div>
            </div>
            <div class="ref-info-chip">
                <div class="ref-info-chip-icon gold"><i class="fa-solid fa-hand-fist"></i></div>
                <div class="ref-info-chip-body">
                    <p class="ref-info-chip-label">Babak</p>
                    <p class="ref-info-chip-value" title="{{ $activeRoundLabel }}">{{ $activeRoundLabel }}</p>
                </div>
            </div>
        </div>

        <div class="ref-technique-panel">
            <div class="ref-technique-hdr">
                <div class="ref-technique-icon"><i class="fa-solid fa-hand-fist"></i></div>
                <div>
                    <p class="ref-technique-label">Teknik</p>
                    <p class="ref-technique-title">Komposisi / Teknik yang Diuji</p>
                </div>
            </div>
            @if(count($activeTechniqueList))
                <ol class="ref-technique-list">
                    @foreach($activeTechniqueList as $technique)
                        <li>{{ $technique }}</li>
                    @endforeach
                </ol>
            @else
                <p class="ref-technique-text">{{ $activeTechniqueLabel }}</p>
            @endif
        </div>

        @if($activeMatch->draft_type === 'embu')
            {{-- ── EMBU FORM ── --}}
            <div class="ref-form-panel">

                {{-- Goho --}}
                <div class="ref-score-group">
                    <div class="ref-score-group-hdr">
                        <i class="fa-solid fa-diamond" style="color:var(--red);"></i>
                        Goho — Teknik Keras
                    </div>
                    <div class="ref-score-items">
                        @php
                            $gohoItems = [
                                'goho_1' => ['label' => 'Goho 1', 'desc' => 'Serangan, bertahan, balasan'],
                                'goho_2' => ['label' => 'Goho 2', 'desc' => 'Lima unsur serangan'],
                                'goho_3' => ['label' => 'Goho 3', 'desc' => 'Kombinasi & timing'],
                            ];
                        @endphp
                        @foreach($gohoItems as $key => $item)
                        <div class="ref-score-row">
                            <div style="flex:1;min-width:0;">
                                <span class="ref-score-label">{{ $item['label'] }}</span>
                                <div class="ref-score-desc">{{ $item['desc'] }}</div>
                            </div>
                            <div class="ref-score-input-wrap">
                                <input type="number"
                                    wire:model.lazy="embuItems.{{ $key }}"
                                    class="ref-score-input"
                                    onfocus="window.refereeScoreInputFocus?.(this)"
                                    onblur="window.refereeScoreInputBlur?.(this)"
                                    min="0" max="10" step="0.1"
                                    placeholder="0.0">
                                <span class="ref-score-range-hint">Isi nilai 8.0 – 10.0</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Juho --}}
                <div class="ref-score-group">
                    <div class="ref-score-group-hdr">
                        <i class="fa-solid fa-diamond" style="color:#2980b9;"></i>
                        Juho — Teknik Lunak
                    </div>
                    <div class="ref-score-items">
                        @php
                            $juhoItems = [
                                'juho_1' => ['label' => 'Juho 1', 'desc' => 'Shuha, nukiwaza, gyaku waza'],
                                'juho_2' => ['label' => 'Juho 2', 'desc' => 'Nage waza, katame waza'],
                                'juho_3' => ['label' => 'Juho 3', 'desc' => 'Kelancaran & kontrol'],
                            ];
                        @endphp
                        @foreach($juhoItems as $key => $item)
                        <div class="ref-score-row">
                            <div style="flex:1;min-width:0;">
                                <span class="ref-score-label">{{ $item['label'] }}</span>
                                <div class="ref-score-desc">{{ $item['desc'] }}</div>
                            </div>
                            <div class="ref-score-input-wrap">
                                <input type="number"
                                    wire:model.lazy="embuItems.{{ $key }}"
                                    class="ref-score-input"
                                    onfocus="window.refereeScoreInputFocus?.(this)"
                                    onblur="window.refereeScoreInputBlur?.(this)"
                                    min="0" max="10" step="0.1"
                                    placeholder="0.0">
                                <span class="ref-score-range-hint">Isi nilai 8.0 – 10.0</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Ekspresi --}}
                <div class="ref-score-group">
                    <div class="ref-score-group-hdr">
                        <i class="fa-solid fa-star" style="color:#b8860b;"></i>
                        Ekspresi — Irama & Penampilan
                    </div>
                    <div class="ref-score-items">
                        @php
                            $ekspresiItems = [
                                'ekspresi_1' => ['label' => 'Ekspresi 1', 'desc' => 'Rangkaian, Irama, Harmoni'],
                                'ekspresi_2' => ['label' => 'Ekspresi 2', 'desc' => 'Tai gamae, Kuda-kuda, Keindahan'],
                                'ekspresi_3' => ['label' => 'Ekspresi 3', 'desc' => 'Semangat, Disiplin'],
                                'ekspresi_4' => ['label' => 'Ekspresi 4', 'desc' => 'Nafas, Pandangan mata, Zanshin'],
                            ];
                        @endphp
                        @foreach($ekspresiItems as $key => $item)
                        <div class="ref-score-row">
                            <div style="flex:1;min-width:0;">
                                <span class="ref-score-label">{{ $item['label'] }}</span>
                                <div class="ref-score-desc">{{ $item['desc'] }}</div>
                            </div>
                            <div class="ref-score-input-wrap">
                                <input type="number"
                                    wire:model.lazy="embuItems.{{ $key }}"
                                    class="ref-score-input"
                                    onfocus="window.refereeScoreInputFocus?.(this)"
                                    onblur="window.refereeScoreInputBlur?.(this)"
                                    min="0" max="10" step="0.1"
                                    placeholder="0.0">
                                <span class="ref-score-range-hint">Isi nilai 8.0 – 10.0</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total Banner --}}
                <div class="ref-total-banner">
                    <div class="ref-total-left">
                        <div class="ref-total-label">Total Skor</div>
                        <div class="ref-total-sub">Dihitung otomatis (10 aspek)</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="ref-total-val">{{ number_format($totalScore, 1) }}</div>
                        @if($totalScore > 0)
                        <div class="ref-avg-val">Rata-rata: {{ number_format($totalScore / 10, 2) }}</div>
                        @endif
                    </div>
                </div>

                {{-- Notes --}}
                <div class="ref-notes-wrap">
                    <div class="ref-notes-hdr">
                        <i class="fa-solid fa-pen-line" style="font-size:10px;"></i>
                        Catatan Wasit (Opsional)
                    </div>
                    <textarea wire:model="notes" class="ref-notes-textarea" rows="3" placeholder="Ketik catatan di sini..."></textarea>
                </div>

                {{-- Actions --}}
                <div class="ref-actions">
                    <button wire:click="resetForm" class="ref-btn-reset">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <button wire:click="submitScore" class="ref-btn-submit">
                        <span wire:loading.remove wire:target="submitScore">
                            <i class="fa-solid fa-paper-plane"></i> Simpan Penilaian
                        </span>
                        <span wire:loading wire:target="submitScore">
                            <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
                        </span>
                    </button>
                </div>
            </div>

        @else
            {{-- ── RANDORI INFO ── --}}
            <div class="ref-randori-info">
                <i class="fa-solid fa-circle-info"></i>
                <h3>Kategori Randori Aktif</h3>
                <p>Penilaian kategori Randori dilakukan langsung oleh <strong>Panitera</strong> di meja pertandingan. Anda tidak perlu memasukkan skor melalui panel ini.</p>
            </div>
        @endif

    @elseif($activeMatch)
        {{-- Waiting — peserta belum dipanggil --}}
        <div class="ref-wait" style="margin: 0 16px 16px;">
            <div class="ref-wait-icon"><i class="fa-solid fa-user-clock"></i></div>
            <h3>Persiapan: {{ $activeMatch->name }}</h3>
            <p>Mohon tunggu, Panitera akan segera memanggil atlet ke lapangan untuk Anda nilai.</p>
        </div>

    @else
        {{-- No active match --}}
        <div class="ref-wait" style="margin: 0 16px 16px;">
            <div class="ref-wait-icon"><i class="fa-solid fa-broadcast-tower"></i></div>
            <h3>Menunggu Pertandingan</h3>
            <p>Belum ada pertandingan yang dipanggil ke lapangan oleh Panitera.</p>
        </div>
    @endif

</div>
