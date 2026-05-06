<?php

$tmStyle = <<<'STYLE'
        <style>
            .tm-page {
                padding: 24px;
                background: var(--paper, #F7F4EF);
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 20px;
            }

            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                margin: 0 0 4px;
            }

            .tm-hdr p {
                font-size: 12px;
                color: var(--smoke, #7f8c8d);
                margin: 0;
            }

            /* TYPE TABS */
            .tm-tabs {
                display: flex;
                gap: 4px;
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 12px;
                padding: 4px;
                margin-bottom: 20px;
                width: fit-content;
            }

            .tm-tab {
                padding: 8px 24px;
                border-radius: 9px;
                border: none;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                color: var(--smoke, #7f8c8d);
                transition: all .15s;
                background: none;
            }

            .tm-tab.active {
                background: var(--ink, #2c3e50);
                color: #fff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            }

            /* LAYOUT */
            .tm-layout {
                display: grid;
                grid-template-columns: 260px 1fr;
                gap: 16px;
                align-items: start;
            }

            /* LEFT PANEL */
            .tm-left {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
                position: sticky;
                top: 16px;
            }

            .tm-panel-title {
                padding: 14px 16px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                font-family: 'Cinzel', serif;
                font-size: 12px;
                font-weight: 700;
                color: var(--ink, #2c3e50);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            /* FILTER SELECT */
            .tm-filter-group {
                padding: 12px 14px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
            }

            .tm-filter-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke, #7f8c8d);
                margin-bottom: 6px;
            }

            .tm-filter-sel {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 9px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink, #2c3e50);
                background: #fff;
                outline: none;
                cursor: pointer;
            }

            .tm-filter-sel:focus {
                border-color: var(--red, #c0392b);
            }
            
            .tm-filter-input {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 9px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink, #2c3e50);
                background: #fff;
                outline: none;
            }

            .tm-filter-input:focus {
                border-color: var(--red, #c0392b);
            }

            /* STATS BAR / COURTS */
            .tm-stats {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 12px;
                margin-bottom: 20px;
            }

            .tm-stat-pill {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                padding: 14px;
                position: relative;
                overflow: hidden;
            }

            .tm-stat-pill:hover {
                border-color: #bbb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
            }

            /* RIGHT PANEL */
            .tm-right {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            /* CARD */
            .tm-card {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
            }

            .tm-card-head {
                padding: 14px 18px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                display: flex;
                align-items: center;
                gap: 10px;
                background: var(--paper, #F7F4EF);
            }

            .tm-card-body {
                padding: 0;
            }

            /* DRAWING RESULT TABLE */
            .draw-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12.5px;
            }

            .draw-table th {
                padding: 10px 14px;
                background: #fdfbf7;
                font-size: 10px;
                color: var(--smoke, #7f8c8d);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                text-align: left;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
            }

            .draw-table td {
                padding: 12px 14px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                vertical-align: middle;
            }

            .draw-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .draw-badge.randori {
                background: rgba(192, 57, 43, .1);
                color: var(--red, #c0392b);
            }

            .draw-badge.embu {
                background: rgba(39, 174, 96, .1);
                color: #27ae60;
            }
            
            /* ACTION BUTTONS */
            .btn-gen {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 14px;
                border-radius: 8px;
                border: none;
                font-size: 11px;
                font-weight: 700;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
                text-transform: uppercase;
                letter-spacing: .05em;
            }

            .btn-gen.primary {
                background: var(--ink, #2c3e50);
                color: #fff;
            }

            .btn-gen.primary:hover {
                background: #1a252f;
                transform: translateY(-1px);
            }

            .btn-gen.secondary {
                background: #2980b9;
                color: #fff;
            }

            .btn-gen.secondary:hover {
                background: #1f6391;
                transform: translateY(-1px);
            }
            
            .btn-gen.danger {
                background: var(--red, #c0392b);
                color: #fff;
            }

            .btn-gen.ghost {
                background: #fff;
                color: var(--smoke, #7f8c8d);
                border: 1px solid var(--paper2, #e0dcd3);
            }

            @media(max-width:900px) {
                .tm-layout {
                    grid-template-columns: 1fr;
                }
                .tm-left {
                    position: static;
                }
            }
            
            /* Court Specific */
            .court-status {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
            }
            .court-title {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                text-transform: uppercase;
                font-family: 'Cinzel', serif;
            }
            .court-match-name {
                font-size: 12px;
                font-weight: 700;
                color: #27ae60;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .court-links {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 6px;
                margin-top: 12px;
            }
            .court-link {
                background: #fdfbf7;
                border: 1px solid var(--paper2);
                border-radius: 6px;
                padding: 6px;
                text-align: center;
                font-size: 9px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                transition: all .15s;
                text-decoration: none;
            }
            .court-link:hover {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
            }
            .court-ref-list {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px dashed var(--paper2);
            }
            .court-ref-item {
                font-size: 10px;
                color: var(--ink);
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 4px;
            }
        </style>
STYLE;

echo "Done\n";
