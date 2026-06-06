<div wire:poll="loadActiveMatch" class="ref-scoring-shell">
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
                               REFEREE SCORING DASHBOARD — Mobile Premium
                            ══════════════════════════════════════════════════════ */

            body.referee-scoring-immersive {
                overflow: auto;
            }

            /* ── MATCH INFO CHIPS (kontingen, court, pool, teknik) ── */
            .ref-info-chips {
                padding: 0 16px 12px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .ref-info-chip {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 12px;
                padding: 10px 12px;
                display: flex;
                align-items: flex-start;
                gap: 9px;
            }

            .ref-info-chip-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
            }

            .ref-info-chip-icon.red {
                background: rgba(192, 57, 43, .1);
                color: var(--red);
            }

            .ref-info-chip-icon.blue {
                background: rgba(52, 152, 219, .1);
                color: #2980b9;
            }

            .ref-info-chip-icon.green {
                background: rgba(39, 174, 96, .1);
                color: #27ae60;
            }

            .ref-info-chip-icon.gold {
                background: rgba(212, 168, 67, .12);
                color: #b8860b;
            }

            .ref-info-chip-body {
                min-width: 0;
            }

            .ref-info-chip-label {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .1em;
                color: var(--smoke);
                margin: 0 0 2px;
            }

            .ref-info-chip-value {
                font-size: 18px;
                font-weight: 700;
                color: var(--ink);
                margin: 0;
                white-space: normal;
                overflow-wrap: anywhere;
                word-break: break-word;
                font-family: 'DM Sans', sans-serif;
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
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 18px 10px;
                gap: 10px;
                flex-wrap: wrap;
            }

            .ref-judge-info {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .ref-judge-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                background: rgba(192, 57, 43, .1);
                color: var(--red);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }

            .ref-judge-name {
                font-size: 19px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 1px;
            }

            .ref-judge-sub {
                font-size: 14px;
                color: var(--smoke);
                margin: 0;
            }

            .ref-judge-badge {
                padding: 6px 13px;
                background: var(--ink);
                color: var(--gold-lt);
                border-radius: 20px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Cinzel', serif;
                letter-spacing: .05em;
                white-space: nowrap;
            }

            /* ── SYNC INDICATOR ── */
            .ref-sync {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 8px 0 12px;
                font-size: 13px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .1em;
            }

            .ref-sync i {
                color: #27ae60;
            }

            /* ── WARNING BANNER ── */
            .ref-warning {
                margin: 0 16px 12px;
                padding: 12px 14px;
                background: rgba(245, 158, 11, .08);
                border: 1px solid rgba(245, 158, 11, .25);
                border-radius: 12px;
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            .ref-warning i {
                color: #d97706;
                font-size: 16px;
                margin-top: 2px;
                flex-shrink: 0;
            }

            .ref-warning-title {
                font-size: 18px;
                font-weight: 700;
                color: #92400e;
                margin: 0 0 2px;
            }

            .ref-warning-text {
                font-size: 15px;
                color: #78350f;
                margin: 0;
                line-height: 1.5;
            }

            /* ── MATCH HEADER ── */
            .ref-match-hdr {
                background: var(--ink);
                margin: 0 16px 12px;
                border-radius: 16px;
                padding: 16px 18px 14px;
                position: relative;
                overflow: hidden;
            }

            .ref-match-hdr::after {
                content: '';
                position: absolute;
                right: -20px;
                bottom: -20px;
                width: 100px;
                height: 100px;
                background: rgba(192, 57, 43, .15);
                border-radius: 50%;
            }

            .ref-live-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 3px 10px;
                background: var(--red);
                color: #fff;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: .1em;
                margin-bottom: 8px;
            }

            .ref-live-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #fff;
                animation: blink .8s ease-in-out infinite alternate;
            }

            @keyframes blink {
                from {
                    opacity: 1;
                }

                to {
                    opacity: .3;
                }
            }

            .ref-match-type {
                font-size: 12.5px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .15em;
                margin-bottom: 4px;
            }

            .ref-match-name {
                font-family: 'Cinzel', serif;
                font-size: 27px;
                font-weight: 700;
                color: #fff;
                margin: 0 0 2px;
            }

            .ref-match-sub {
                font-size: 15px;
                color: rgba(255, 255, 255, .4);
                margin: 0;
            }

            /* ── TECHNIQUE PANEL ── */
            .ref-technique-panel {
                margin: 0 16px 12px;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 14px;
                padding: 14px 16px;
                position: sticky;
                top: 12px;
                z-index: 50;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            }

            .ref-detail-panel {
                margin: 0 16px 12px;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 14px;
                padding: 14px 16px;
                position: sticky;
                top: 12px;
                z-index: 50;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            }

            .ref-detail-grid {
                display: grid;
                gap: 12px;
            }

            .ref-detail-row {
                display: grid;
                grid-template-columns: 170px 1fr;
                align-items: center;
                gap: 14px;
            }

            .ref-detail-label {
                font-size: 19px;
                font-weight: 800;
                color: #24364b;
                text-transform: uppercase;
            }

            .ref-detail-value {
                min-width: 0;
                font-size: 22px;
                font-weight: 800;
                color: var(--ink);
                line-height: 1.35;
                padding-bottom: 10px;
                border-bottom: 3px solid #b8c9dd;
                word-break: break-word;
            }

            .ref-detail-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .ref-detail-badge {
                display: flex;
                align-items: center;
                gap: 10px;
                min-height: 62px;
                padding: 0 18px;
                border-radius: 999px;
                border: 1.5px solid #b8d0ec;
                background: #fff;
                font-size: 18px;
                font-weight: 700;
                color: var(--ink);
            }

            .ref-detail-badge-label {
                font-size: 16px;
                font-weight: 800;
                color: #24364b;
            }

            .ref-technique-hdr {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            .ref-technique-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                background: rgba(212, 168, 67, .12);
                color: #b8860b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                flex-shrink: 0;
            }

            .ref-technique-label {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .12em;
                color: var(--smoke);
                margin: 0 0 2px;
            }

            .ref-technique-title {
                font-size: 19px;
                font-weight: 700;
                color: var(--ink);
                margin: 0;
            }

            .ref-technique-text {
                font-size: 18px;
                line-height: 1.7;
                color: var(--ink);
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
                margin: 0 16px 16px;
                padding: 32px 24px;
                text-align: center;
                background: #fff;
                border-radius: 16px;
                border: 1px solid var(--paper2);
            }

            .ref-wait-icon {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                background: var(--paper);
                margin: 0 auto 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                color: var(--smoke);
                animation: pulse-slow 2.5s ease-in-out infinite;
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: .4;
                }
            }

            .ref-wait h3 {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 6px;
            }

            .ref-wait p {
                font-size: 15px;
                color: var(--smoke);
                margin: 0;
                line-height: 1.6;
                max-width: 260px;
                margin-inline: auto;
            }

            /* ── SCORE FORM PANEL ── */
            .ref-form-panel {
                padding: 0 16px 16px;
            }

            .ref-score-table {
                background: #fff;
                border-radius: 14px;
                border: 1px solid var(--paper2);
                margin-bottom: 12px;
                overflow: hidden;
            }

            .ref-score-table-wrap {
                overflow-x: auto;
            }

            .ref-score-table-grid {
                width: 100%;
                min-width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .ref-score-table-grid th:nth-child(1) {
                width: 5%;
            }

            .ref-score-table-grid th:nth-child(2) {
                width: 15%;
            }

            .ref-score-table-grid th:nth-child(3) {
                width: 10%;
            }

            .ref-score-table-grid th:nth-child(4) {
                width: 5%;
            }

            .ref-score-table-grid th:nth-child(5) {
                width: 8%;
            }

            .ref-score-table-grid th:nth-child(6) {
                width: 8%;
            }

            .ref-score-table-grid th,
            .ref-score-table-grid td {
                border-right: 1px solid var(--paper2);
                border-bottom: 1px solid var(--paper2);
                vertical-align: middle;
            }

            .ref-score-table-grid tr>*:last-child {
                border-right: none;
            }

            .ref-score-table-grid thead th {
                background: var(--paper);
                padding: 12px 10px;
                font-size: 11px;
                font-weight: 800;
                color: #24364b;
                text-transform: uppercase;
                letter-spacing: .08em;
                text-align: center;
            }

            .ref-score-table-grid thead th:nth-child(2) {
                text-align: left;
            }

            .ref-score-cell {
                padding: 10px 8px;
                min-width: 0;
            }

            @media (max-width: 1024px) {
                .ref-score-cell {
                    padding: 6px 4px;
                }

                .ref-score-table-grid thead th {
                    padding: 10px 6px;
                    font-size: 10px;
                }
            }

            .ref-score-aspect {
                text-align: center;
                font-size: 18px;
                font-weight: 800;
                color: var(--ink);
                line-height: 1.35;
                background: rgba(247, 244, 239, .65);
                writing-mode: vertical-rl;
                white-space: nowrap;
            }

            .ref-score-label {
                font-size: 18px;
                font-weight: 800;
                color: var(--ink);
                line-height: 1.35;
            }

            .ref-score-desc {
                font-size: 15px;
                color: var(--smoke);
                margin-top: 4px;
                line-height: 1.45;
            }

            .ref-score-weight,
            .ref-score-no,
            .ref-score-standard {
                font-size: 20px;
                font-weight: 700;
                color: var(--ink);
                text-align: center;
            }

            .ref-score-weight-note {
                display: block;
                margin-top: 4px;
                font-size: 10px;
                font-weight: 700;
                color: var(--smoke);
            }

            .ref-score-input-cell {
                text-align: center;
            }

            /* ── SCORE INPUT (range 8–10) ── */
            .ref-score-input-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                width: 100%;
            }

            .ref-score-input {
                width: 100%;
                max-width: 100px;
                padding: 6px 2px;
                border: 1.5px solid var(--paper2);
                border-radius: 10px;
                font-family: 'Cinzel', serif;
                font-size: 30px;
                font-weight: 700;
                text-align: center;
                color: var(--ink);
                background: var(--paper);
                outline: none;
                transition: border .15s, box-shadow .15s;
                -moz-appearance: textfield;
                height: 50px;
            }

            .ref-score-input::-webkit-inner-spin-button,
            .ref-score-input::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .ref-score-input:focus {
                border-color: var(--red);
                background: #fff;
                box-shadow: 0 0 0 3px rgba(192, 57, 43, .1);
            }

            .ref-score-input.is-valid {
                border-color: #27ae60;
            }

            .ref-score-input.is-invalid {
                border-color: var(--red);
            }

            .ref-score-range-hint {
                font-size: 13px;
                color: var(--smoke);
                letter-spacing: .05em;
            }

            @media (max-width: 1024px) {
                .ref-score-aspect {
                    font-size: 15px;
                }

                .ref-score-label {
                    font-size: 15px;
                }

                .ref-score-desc {
                    font-size: 13px;
                    margin-top: 2px;
                    line-height: 1.3;
                }

                .ref-score-weight,
                .ref-score-no,
                .ref-score-standard {
                    font-size: 16px;
                }

                .ref-score-input {
                    font-size: 22px;
                    max-width: 100px;
                    padding: 8px 4px;
                }

                .ref-score-range-hint {
                    font-size: 11px;
                }
            }

            .ref-score-table-subtotal {
                background: rgba(247, 244, 239, .55);
            }

            .ref-score-subtotal-label {
                text-align: right;
                font-size: 17px;
                font-weight: 800;
                color: #24364b;
            }

            .ref-score-subtotal-value {
                font-family: 'Cinzel', serif;
                font-size: 24px;
                font-weight: 800;
                color: var(--ink);
                text-align: center;
            }

            /* ── TOTAL BANNER ── */
            .ref-total-banner {
                background: var(--ink);
                border-radius: 14px;
                margin-bottom: 12px;
                padding: 16px 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .ref-total-left {}

            .ref-total-label {
                font-size: 14px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .1em;
            }

            .ref-total-sub {
                font-size: 13px;
                color: rgba(255, 255, 255, .3);
                font-style: italic;
                margin-top: 2px;
            }

            .ref-total-val {
                font-family: 'Cinzel', serif;
                font-size: 54px;
                font-weight: 700;
                color: var(--gold-lt);
                line-height: 1;
            }

            .ref-avg-val {
                font-size: 15px;
                color: var(--smoke);
                text-align: right;
                margin-top: 4px;
                font-family: 'DM Sans', sans-serif;
            }

            /* ── NOTES ── */
            .ref-notes-wrap {
                background: #fff;
                border-radius: 14px;
                border: 1px solid var(--paper2);
                margin-bottom: 14px;
                overflow: hidden;
            }

            .ref-notes-hdr {
                padding: 10px 14px;
                border-bottom: 1px solid var(--paper2);
                font-size: 13px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .12em;
                background: var(--paper);
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .ref-notes-textarea {
                width: 100%;
                border: none;
                outline: none;
                padding: 12px 14px;
                font-family: 'DM Sans', sans-serif;
                font-size: 18px;
                color: var(--ink);
                background: transparent;
                resize: none;
                min-height: 80px;
                box-sizing: border-box;
            }

            /* ── ACTION BUTTONS ── */
            .ref-actions {
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 10px;
            }

            .ref-btn-reset {
                padding: 14px;
                background: var(--paper);
                border: 1px solid var(--paper2);
                border-radius: 12px;
                font-size: 16px;
                font-weight: 700;
                color: var(--ink);
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                transition: all .15s;
            }

            .ref-btn-reset:hover {
                background: var(--paper2);
            }

            .ref-btn-submit {
                padding: 14px;
                background: var(--red);
                border: none;
                border-radius: 12px;
                font-size: 16px;
                font-weight: 700;
                color: #fff;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                box-shadow: 0 4px 16px rgba(192, 57, 43, .35);
                transition: all .2s;
            }

            .ref-btn-submit:hover {
                background: var(--red-deep);
            }

            .ref-btn-submit:active {
                transform: scale(.98);
            }

            /* ── RANDORI INFO ── */
            .ref-randori-info {
                margin: 0 16px 16px;
                padding: 24px;
                text-align: center;
                background: #fff;
                border-radius: 14px;
                border: 1px solid var(--paper2);
            }

            .ref-randori-info i {
                font-size: 28px;
                color: #2980b9;
                margin-bottom: 12px;
                display: block;
            }

            .ref-randori-info h3 {
                font-family: 'Cinzel', serif;
                font-size: 19px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 6px;
            }

            .ref-randori-info p {
                font-size: 15px;
                color: var(--smoke);
                margin: 0;
                line-height: 1.6;
                max-width: 280px;
                margin-inline: auto;
            }

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
                .ref-detail-panel,
                .ref-technique-panel,
                .ref-randori-info,
                .ref-wait {
                    margin-inline: 12px;
                }

                .ref-form-panel {
                    padding-inline: 12px;
                }

                .ref-detail-row {
                    grid-template-columns: 1fr;
                    gap: 8px;
                }

                .ref-detail-label {
                    font-size: 17px;
                }

                .ref-detail-value {
                    font-size: 20px;
                }

                .ref-detail-badge {
                    min-height: 56px;
                    font-size: 17px;
                }

                .ref-detail-badge-label {
                    font-size: 15px;
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

                .ref-notes-hdr {
                    line-height: 1.4;
                }

                .ref-score-input-wrap {
                    align-items: center;
                    max-width: 128px;
                    margin-inline: auto;
                }

                .ref-score-input {
                    min-height: 58px;
                    font-size: 28px;
                }

                .ref-score-range-hint {
                    text-align: center;
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
                .ref-score-table-grid {
                    min-width: 820px;
                }

                .ref-technique-panel {
                    padding: 14px 15px;
                }
            }
            /* ── REFEREE UNAVAILABLE / READONLY ── */
            .ref-form-readonly {
                margin: 0 16px 16px;
                background: #fff;
                border-radius: 14px;
                border: 1px solid var(--paper2);
                overflow: hidden;
            }

            .ref-card-hdr {
                padding: 20px;
                background: var(--paper);
                border-bottom: 1px solid var(--paper2);
            }

            .ref-card-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 17px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 12px;
            }

            .ref-match-info {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .ref-info-row {
                display: flex;
                justify-content: space-between;
                font-size: 13px;
            }

            .ref-info-label {
                color: var(--smoke);
                font-weight: 500;
            }

            .ref-info-value {
                color: var(--ink);
                font-weight: 600;
                text-align: right;
            }

            .ref-readonly-overlay {
                padding: 32px 20px;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .ref-readonly-overlay i {
                font-size: 32px;
                color: #e74c3c;
                margin-bottom: 12px;
                display: block;
            }

            .ref-readonly-overlay h3 {
                font-family: 'Cinzel', serif;
                font-size: 17px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 6px;
            }

            .ref-readonly-overlay p {
                font-size: 13px;
                color: var(--smoke);
                margin: 0;
                line-height: 1.5;
                max-width: 280px;
                margin-inline: auto;
            }

            /* ── DIGITAL SIGNATURE ── */
            .ref-sig-wrap {
                background: #fff;
                border-radius: 14px;
                border: 1px solid var(--paper2);
                margin-bottom: 14px;
                overflow: hidden;
            }

            .ref-sig-hdr {
                padding: 10px 14px;
                border-bottom: 1px solid var(--paper2);
                font-size: 13px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .12em;
                background: var(--paper);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .ref-sig-hdr-title {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .ref-sig-body {
                padding: 14px;
                position: relative;
                background: #fdfdfd;
            }

            .ref-sig-canvas-container {
                position: relative;
                width: 100%;
                height: 380px;
                background: #ffffff;
                border: 1.5px dashed #b8c9dd;
                border-radius: 10px;
                overflow: hidden;
                touch-action: none;
            }

            .ref-sig-canvas-container::before {
                content: '';
                position: absolute;
                left: 10%;
                right: 10%;
                bottom: 40px;
                border-bottom: 1px dashed rgba(36, 54, 75, 0.15);
                pointer-events: none;
            }

            .ref-sig-canvas-container::after {
                content: 'Tanda Tangan di Sini';
                position: absolute;
                left: 50%;
                bottom: 15px;
                transform: translateX(-50%);
                font-size: 12px;
                color: rgba(36, 54, 75, 0.35);
                font-family: 'DM Sans', sans-serif;
                pointer-events: none;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            .ref-sig-canvas {
                width: 100%;
                height: 100%;
                display: block;
                cursor: crosshair;
            }

            .ref-sig-clear-btn {
                padding: 6px 12px;
                background: #f1f5f9;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                color: var(--ink);
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 4px;
                transition: all 0.15s;
                border-style: solid;
            }

            .ref-sig-clear-btn:hover {
                background: #e2e8f0;
                border-color: #94a3b8;
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
                    input.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    input.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                };

                const syncFullscreenButton = () => {
                    document.querySelectorAll('[data-ref-fullscreen-btn]').forEach((button) => {
                        const icon = button.querySelector('i');
                        const label = button.querySelector('[data-ref-fullscreen-label]');
                        const isFullscreen = !!document.fullscreenElement;

                        if (icon) {
                            icon.className = isFullscreen ?
                                'fa-solid fa-compress' :
                                'fa-solid fa-expand';
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
                    document.addEventListener('DOMContentLoaded', initRefereeScoringView, {
                        once: true
                    });
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
                window.addEventListener('pointerdown', attemptFullscreen, {
                    once: true
                });
            })();
        </script>
    @endpush

    <button type="button" class="ref-fullscreen-btn" data-ref-fullscreen-btn
        onclick="window.refereeScoringFullscreen?.()">
        <i class="fa-solid fa-expand"></i>
        <span data-ref-fullscreen-label>Fullscreen</span>
    </button>

    {{-- ── STATUS BAR ── --}}
    <div class="ref-statusbar">
        <div class="ref-judge-info">
            <div class="ref-judge-icon"><i class="fa-solid fa-gavel"></i></div>
            <div>
                <p class="ref-judge-name">
                    @if ($this->isTabletMode)
                        {{ $referee?->user?->name ?? 'Belum Ditugaskan' }}
                    @else
                        {{ $referee?->user?->name ?? (auth()->user()?->name ?? 'Wasit') }}
                    @endif
                </p>
                <p class="ref-judge-sub">
                    @if ($this->isTabletMode)
                        <span style="color:var(--red); font-weight:700;">Acting as {{ $this->judgeLabel }}</span>
                    @else
                        Wasit Juri Aktif
                    @endif
                </p>
            </div>
        </div>
        @if ($judgeIndex)
            <div class="ref-judge-badge">{{ $this->judgeLabel }}</div>
        @endif
    </div>

    {{-- ── SYNC BADGE ── --}}
    <div class="ref-sync">
        <i class="fa-solid fa-satellite-dish fa-spin-pulse"></i>
        Sinkronisasi Otomatis Aktif
    </div>

    {{-- ── WARNING: bukan wasit terdaftar ── --}}
    @if (!$referee)
        <div class="ref-warning"
            style="background: {{ $this->isTabletMode ? 'rgba(52,152,219,.08)' : 'rgba(245,158,11,.08)' }}; border-color: {{ $this->isTabletMode ? 'rgba(52,152,219,.25)' : 'rgba(245,158,11,.25)' }};">
            <i class="fa-solid {{ $this->isTabletMode ? 'fa-info-circle' : 'fa-triangle-exclamation' }}"
                style="color: {{ $this->isTabletMode ? '#2980b9' : '#d97706' }};"></i>
            <div>
                <p class="ref-warning-title" style="color: {{ $this->isTabletMode ? '#24364b' : '#92400e' }};">
                    {{ $this->isTabletMode ? 'Menunggu Penugasan Wasit' : 'Akun Bukan Wasit Terdaftar' }}
                </p>
                <p class="ref-warning-text" style="color: {{ $this->isTabletMode ? '#2c3e50' : '#78350f' }};">
                    @if ($this->isTabletMode)
                        Tablet ini sudah siap digunakan. Silakan tunggu Panitia/Admin menugaskan Wasit untuk shift ini
                        di Court {{ Auth::user()->court_id }}.
                    @else
                        Akun Anda tidak terdaftar sebagai wasit. Scoring tidak dapat dikirim. Pastikan login menggunakan
                        akun wasit yang benar.
                    @endif
                </p>
            </div>
        </div>
    @endif

    {{-- ── MATCH ACTIVE ── --}}
    @if ($isFormOpen)

        {{-- Match Header --}}
        <div class="ref-match-hdr">
            <div class="ref-live-badge"><span class="ref-live-dot"></span> LIVE ON COURT</div>
            <p class="ref-match-type">{{ strtoupper($activeMatch->draft_type) }}</p>
            <h3 class="ref-match-name">{{ $activeMatch->name }}</h3>
            <p class="ref-match-sub">Berikan penilaian terbaik Anda secara objektif.</p>
        </div>

        @php
            $courtName = $assignedCourt?->name ?? '-';
            $poolName = $activeMatch->ageGroup?->name ?? ($activeMatch->pool?->name ?? '-');
        @endphp

        <div class="ref-info-chips">
            <div class="ref-info-chip">
                <div class="ref-info-chip-icon red"><i class="fa-solid fa-flag"></i></div>
                <div class="ref-info-chip-body">
                    <p class="ref-info-chip-label">Kontingen</p>
                    <p class="ref-info-chip-value" title="{{ $this->activeContestantLabel }}">
                        {{ $activeContingentName }}</p>
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
            @if (count($activeTechniqueList))
                <ol class="ref-technique-list">
                    @foreach ($activeTechniqueList as $technique)
                        <li>{{ $technique }}</li>
                    @endforeach
                </ol>
            @else
                <p class="ref-technique-text">{{ $activeTechniqueLabel }}</p>
            @endif
        </div>

        @if ($activeMatch->draft_type === 'embu')
            {{-- ── EMBU FORM ── --}}
            @if ($referee)
                <div class="ref-form-panel">
                    @php
                        $techniqueRows = [
                            ['key' => 'goho_1', 'aspect' => 'GOHO', 'desc' => 'Serangan, bertahan, balasan', 'no' => 1],
                            ['key' => 'goho_2', 'aspect' => 'GOHO', 'desc' => 'Lima unsur serangan', 'no' => 2],
                            ['key' => 'goho_3', 'aspect' => 'GOHO', 'desc' => 'Kombinasi & timing', 'no' => 3],
                            ['key' => 'juho_1', 'aspect' => 'JUHO', 'desc' => 'Shuha, nukiwaza, gyaku waza', 'no' => 4],
                            ['key' => 'juho_2', 'aspect' => 'JUHO', 'desc' => 'Nage waza, katame waza, dll', 'no' => 5],
                            ['key' => 'juho_3', 'aspect' => 'JUHO', 'desc' => 'Kelancaran & kontrol', 'no' => 6],
                        ];
                        $expressionRows = [
                            [
                                'key' => 'ekspresi_1',
                                'aspect' => 'Ekspresi',
                                'desc' => 'Rangkaian, Irama, Harmoni',
                                'no' => 1,
                            ],
                            [
                                'key' => 'ekspresi_2',
                                'aspect' => 'Ekspresi',
                                'desc' => 'Tai gamae, Kuda-kuda, Keindahan',
                                'no' => 2,
                            ],
                            ['key' => 'ekspresi_3', 'aspect' => 'Ekspresi', 'desc' => 'Semangat, Disiplin', 'no' => 3],
                            [
                                'key' => 'ekspresi_4',
                                'aspect' => 'Ekspresi',
                                'desc' => 'Nafas, Pandangan mata, Zanshin',
                                'no' => 4,
                            ],
                        ];
                        $techniqueSubtotal = collect($techniqueRows)->sum(
                            fn($row) => is_numeric($embuItems[$row['key']] ?? null)
                                ? (float) $embuItems[$row['key']]
                                : 0,
                        );
                        $expressionSubtotal = collect($expressionRows)->sum(
                            fn($row) => is_numeric($embuItems[$row['key']] ?? null)
                                ? (float) $embuItems[$row['key']]
                                : 0,
                        );
                    @endphp

                    <div class="ref-score-table">
                        <div class="ref-score-table-wrap">
                            <table class="ref-score-table-grid">
                                <thead>
                                    <tr>
                                        <th>Aspek</th>
                                        <th>Deskripsi</th>
                                        <th>Bobot</th>
                                        <th>No</th>
                                        <th>Nilai</th>
                                        <th>Standar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($techniqueRows as $index => $row)
                                        <tr>
                                            @if ($index === 0)
                                                <td class="ref-score-cell ref-score-aspect"
                                                    rowspan="{{ count($techniqueRows) }}">
                                                    Penguasaan Teknik (60)
                                                </td>
                                            @endif
                                            @if ($index === 0 || $index === 3)
                                                <td class="ref-score-cell ref-score-desc-cell" rowspan="3">
                                                    @if ($index === 0)
                                                        <div style="line-height: 1.6;">
                                                            <strong
                                                                style="color: var(--ink); font-size: 14px;">GOHO</strong><span
                                                                style="color: var(--ink); font-size: 14px;"> : Serangan,
                                                                bertahan, serangan balasan, lima unsur serangan dan
                                                                lain-lain</span>
                                                        </div>
                                                    @else
                                                        <div style="line-height: 1.6;">
                                                            <strong
                                                                style="color: var(--ink); font-size: 14px;">JUHO</strong><span
                                                                style="color: var(--ink); font-size: 14px;"> : Shuha,
                                                                nukiwaza, gyaku waza, nage waza, katame waza dan
                                                                lain-lain</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($index === 0)
                                                <td class="ref-score-cell ref-score-weight"
                                                    rowspan="{{ count($techniqueRows) }}">
                                                    <div>
                                                        60
                                                        <span class="ref-score-weight-note">(Masing² 10)</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="ref-score-cell ref-score-no">{{ $row['no'] }}</td>
                                            <td class="ref-score-cell ref-score-input-cell">
                                                <div class="ref-score-input-wrap">
                                                    <input type="text"
                                                        inputmode="decimal"
                                                        wire:model.lazy="embuItems.{{ $row['key'] }}"
                                                        class="ref-score-input"
                                                        onfocus="window.refereeScoreInputFocus?.(this)"
                                                        onblur="window.refereeScoreInputBlur?.(this)"
                                                        oninput="this.value = this.value.replace(',', '.')"
                                                        placeholder="0.0">
                                                    <span class="ref-score-range-hint">Isi 0.0 – 10.0</span>
                                                </div>
                                            </td>
                                            <td class="ref-score-cell ref-score-standard">8</td>
                                        </tr>
                                    @endforeach

                                    <tr class="ref-score-table-subtotal">
                                        <td class="ref-score-cell ref-score-subtotal-label" colspan="5">Sub Total-1
                                        </td>
                                        <td class="ref-score-cell ref-score-subtotal-value">
                                            {{ number_format($techniqueSubtotal, 1) }}</td>
                                    </tr>

                                    @foreach ($expressionRows as $index => $row)
                                        <tr>
                                            @if ($index === 0)
                                                <td class="ref-score-cell ref-score-aspect"
                                                    rowspan="{{ count($expressionRows) }}">
                                                    Ekspresi (40)
                                                </td>
                                            @endif
                                            <td class="ref-score-cell ref-score-desc-cell">
                                                <div class="ref-score-desc"
                                                    style="color: var(--ink); font-weight: 500; font-size: 14px; margin-top: 0;">
                                                    {{ $row['no'] }}. {{ $row['desc'] }}
                                                </div>
                                            </td>
                                            @if ($index === 0)
                                                <td class="ref-score-cell ref-score-weight"
                                                    rowspan="{{ count($expressionRows) }}">
                                                    <div>
                                                        40
                                                        <span class="ref-score-weight-note">(Masing² 10)</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="ref-score-cell ref-score-no">{{ $row['no'] }}</td>
                                            <td class="ref-score-cell ref-score-input-cell">
                                                <div class="ref-score-input-wrap">
                                                    <input type="text"
                                                        inputmode="decimal"
                                                        wire:model.lazy="embuItems.{{ $row['key'] }}"
                                                        class="ref-score-input"
                                                        onfocus="window.refereeScoreInputFocus?.(this)"
                                                        onblur="window.refereeScoreInputBlur?.(this)"
                                                        oninput="this.value = this.value.replace(',', '.')"
                                                        placeholder="0.0">
                                                    <span class="ref-score-range-hint">Isi 0.0 – 10.0</span>
                                                </div>
                                            </td>
                                            <td class="ref-score-cell ref-score-standard">8</td>
                                        </tr>
                                    @endforeach

                                    <tr class="ref-score-table-subtotal">
                                        <td class="ref-score-cell ref-score-subtotal-label" colspan="5">Sub Total-2
                                        </td>
                                        <td class="ref-score-cell ref-score-subtotal-value">
                                            {{ number_format($expressionSubtotal, 1) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Total Banner --}}
                    <div class="ref-total-banner">
                        <div class="ref-total-left">
                            <div class="ref-total-label">Total Skor</div>
                            <div class="ref-total-sub">Sub Total-1 + Sub Total-2 (10 aspek)</div>
                        </div>
                        <div style="text-align:right;">
                            <div class="ref-total-val">{{ number_format($totalScore, 1) }}</div>
                            @if ($totalScore > 0)
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

                    {{-- Digital Signature & Actions Wrapper --}}
                    <div x-data="{
                        localSignature: '{{ $signature }}' || null,
                        activeId: @entangle('currentActiveIdentifier'),
                        isDrawing: false,
                        ctx: null,
                        canvas: null,
                        init() {
                            this.canvas = this.$refs.canvas;
                            this.ctx = this.canvas.getContext('2d');
                            
                            // Prevent scrolling on touch devices
                            const preventDefault = (e) => {
                                if (e.target === this.canvas) {
                                    e.preventDefault();
                                }
                            };
                            this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                            this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                            this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                            
                            this.resizeCanvas();
                            
                            // If we have an initial signature, draw it
                            if (this.localSignature) {
                                this.loadSignature(this.localSignature);
                            }

                            window.addEventListener('resize', () => this.resizeCanvas());
                            
                            this.$watch('activeId', (value) => {
                                this.clear();
                            });
                            
                            window.addEventListener('signature-loaded', (e) => {
                                if (e.detail && e.detail.signature) {
                                    this.localSignature = e.detail.signature;
                                    this.loadSignature(e.detail.signature);
                                } else {
                                    this.clear();
                                }
                            });
                            
                            window.addEventListener('form-reset', () => {
                                this.clear();
                            });
                        },
                        resizeCanvas() {
                            if (!this.canvas || this.canvas.offsetWidth === 0) return;
                            
                            // Save current canvas content
                            const temp = this.canvas.toDataURL();
                            
                            const rect = this.canvas.getBoundingClientRect();
                            this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                            this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                            this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                            
                            this.ctx.strokeStyle = '#000000';
                            this.ctx.lineWidth = 3;
                            this.ctx.lineCap = 'round';
                            this.ctx.lineJoin = 'round';
                            
                            if (temp && temp !== 'data:,') {
                                const img = new Image();
                                img.onload = () => {
                                    this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                };
                                img.src = temp;
                            }
                        },
                        getMousePos(e) {
                            const rect = this.canvas.getBoundingClientRect();
                            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                            return {
                                x: clientX - rect.left,
                                y: clientY - rect.top
                            };
                        },
                        startDrawing(e) {
                            this.isDrawing = true;
                            const pos = this.getMousePos(e);
                            this.ctx.beginPath();
                            this.ctx.moveTo(pos.x, pos.y);
                        },
                        draw(e) {
                            if (!this.isDrawing) return;
                            const pos = this.getMousePos(e);
                            this.ctx.lineTo(pos.x, pos.y);
                            this.ctx.stroke();
                        },
                        stopDrawing() {
                            if (!this.isDrawing) return;
                            this.isDrawing = false;
                            this.save();
                        },
                        clear() {
                            if (!this.canvas) return;
                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                            this.localSignature = null;
                        },
                        save() {
                            const dataUrl = this.canvas.toDataURL('image/png');
                            const blank = document.createElement('canvas');
                            blank.width = this.canvas.width;
                            blank.height = this.canvas.height;
                            if (dataUrl === blank.toDataURL('image/png')) {
                                this.localSignature = null;
                            } else {
                                this.localSignature = dataUrl;
                            }
                        },
                        loadSignature(dataUrl) {
                            const img = new Image();
                            img.onload = () => {
                                const rect = this.canvas.getBoundingClientRect();
                                this.ctx.clearRect(0, 0, rect.width, rect.height);
                                this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                            };
                            img.src = dataUrl;
                        }
                    }">
                        {{-- Digital Signature --}}
                        <div class="ref-sig-wrap">
                            <div class="ref-sig-hdr">
                                <span class="ref-sig-hdr-title">
                                    <i class="fa-solid fa-signature"></i>
                                    Tanda Tangan Digital (Wajib)
                                </span>
                                <button type="button" @click="clear()" class="ref-sig-clear-btn">
                                    <i class="fa-solid fa-eraser"></i> Hapus
                                </button>
                            </div>
                            <div class="ref-sig-body">
                                <div class="ref-sig-canvas-container" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="ref-sig-canvas"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="ref-actions">
                            <button wire:click="resetForm" class="ref-btn-reset">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </button>
                            <button type="button" @click="$wire.submitScore(localSignature)" class="ref-btn-submit">
                                <span wire:loading.remove wire:target="submitScore">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan Penilaian
                                </span>
                                <span wire:loading wire:target="submitScore">
                                    <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="ref-form-readonly">
                    <div class="ref-card-hdr">
                        <h2>Penilaian {{ $activeMatch->name ?? '—' }}</h2>
                        <div class="ref-match-info">
                            <div class="ref-info-row">
                                <span class="ref-info-label">Arena</span>
                                <span class="ref-info-value">{{ $assignedCourt->name ?? '—' }}</span>
                            </div>
                            <div class="ref-info-row">
                                <span class="ref-info-label">Wasit</span>
                                <span class="ref-info-value">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ref-readonly-overlay">
                        <div>
                            <i class="fa-solid fa-user-slash"></i>
                            <h3>Wasit Tidak Tersedia</h3>
                            <p>Anda tidak terdaftar sebagai wasit untuk pertandingan ini atau wasit belum diatur.</p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            {{-- ── RANDORI INFO ── --}}
            <div class="ref-randori-info">
                <i class="fa-solid fa-circle-info"></i>
                <h3>Kategori Randori Aktif</h3>
                <p>Penilaian kategori Randori dilakukan langsung oleh <strong>Panitera</strong> di meja pertandingan.
                    Anda tidak perlu memasukkan skor melalui panel ini.</p>
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
