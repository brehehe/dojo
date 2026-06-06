<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\KyuLevel;
use App\Models\MatchNumber\MatchNumber;

class WelcomeController extends Controller
{
    /**
     * Template 1: Dark Cinematic Hero with Auto-Slider (default)
     */
    public function template1()
    {
        // Techniques grouped by kyu_level for the Teknik section
        $techniquesByLevel = KyuLevel::orderBy('order')
            ->with(['techniques' => fn ($q) => $q->orderBy('order')])
            ->whereHas('techniques')
            ->get();

        // Live stats from DB
        $stats = cache()->remember('welcome_stats', 600, function () {
            return [
                'peserta' => Athlete::count() ?: '500+',
                'nomor' => MatchNumber::count() ?: '30+',
                'kontingen' => Contingent::count() ?: '20+',
            ];
        });

        return view('welcome.1', compact('techniquesByLevel', 'stats'));
    }

    /**
     * Template 2: Light Premium Championship Branding
     */
    public function template2()
    {
        return view('welcome.2');
    }

    /**
     * Template 3: Bold Split-Screen with Rundown Section
     */
    public function template3()
    {
        return view('welcome.3');
    }

    /**
     * Template 4: Glassmorphism / Gradient Blurred Hero (default orange)
     */
    public function template4()
    {
        return $this->template4Color('orange');
    }

    /**
     * Template 4 with dynamic color theming.
     *
     * @param  string  $color  One of: orange, blue, red, purple, emerald, rose, indigo, teal, amber, cyan
     */
    public function template4Color(string $color = 'orange')
    {
        /** @var array<string, array<string, string>> $themes */
        $themes = [
            'orange' => [
                'label' => 'Orange',
                'accent' => '#f97316',
                'accentHover' => '#fb923c',
                'accentDim' => 'rgba(249,115,22,0.10)',
                'accentBorder' => 'rgba(249,115,22,0.25)',
                'accentText' => '#c2410c',
                'orb1' => 'rgba(249,115,22,0.20)',
                'orb2' => 'rgba(234,88,12,0.10)',
                'orb3' => 'rgba(253,186,116,0.12)',
                'stroke' => 'rgba(251,146,60,0.55)',
                'bgFrom' => '#fff7ed',
                'bgTo' => '#fef3c7',
                'ctaGradFrom' => 'rgba(249,115,22,0.07)',
                'ctaGradTo' => 'rgba(249,115,22,0.02)',
            ],
            'blue' => [
                'label' => 'Blue',
                'accent' => '#3b82f6',
                'accentHover' => '#60a5fa',
                'accentDim' => 'rgba(59,130,246,0.10)',
                'accentBorder' => 'rgba(59,130,246,0.25)',
                'accentText' => '#1d4ed8',
                'orb1' => 'rgba(59,130,246,0.18)',
                'orb2' => 'rgba(37,99,235,0.10)',
                'orb3' => 'rgba(147,197,253,0.12)',
                'stroke' => 'rgba(96,165,250,0.55)',
                'bgFrom' => '#eff6ff',
                'bgTo' => '#dbeafe',
                'ctaGradFrom' => 'rgba(59,130,246,0.07)',
                'ctaGradTo' => 'rgba(59,130,246,0.02)',
            ],
            'red' => [
                'label' => 'Red',
                'accent' => '#ef4444',
                'accentHover' => '#f87171',
                'accentDim' => 'rgba(239,68,68,0.10)',
                'accentBorder' => 'rgba(239,68,68,0.25)',
                'accentText' => '#b91c1c',
                'orb1' => 'rgba(239,68,68,0.18)',
                'orb2' => 'rgba(185,28,28,0.10)',
                'orb3' => 'rgba(252,165,165,0.12)',
                'stroke' => 'rgba(248,113,113,0.55)',
                'bgFrom' => '#fff1f2',
                'bgTo' => '#fee2e2',
                'ctaGradFrom' => 'rgba(239,68,68,0.07)',
                'ctaGradTo' => 'rgba(239,68,68,0.02)',
            ],
            'purple' => [
                'label' => 'Purple',
                'accent' => '#a855f7',
                'accentHover' => '#c084fc',
                'accentDim' => 'rgba(168,85,247,0.10)',
                'accentBorder' => 'rgba(168,85,247,0.25)',
                'accentText' => '#7e22ce',
                'orb1' => 'rgba(168,85,247,0.18)',
                'orb2' => 'rgba(109,40,217,0.10)',
                'orb3' => 'rgba(216,180,254,0.12)',
                'stroke' => 'rgba(196,132,252,0.55)',
                'bgFrom' => '#faf5ff',
                'bgTo' => '#f3e8ff',
                'ctaGradFrom' => 'rgba(168,85,247,0.07)',
                'ctaGradTo' => 'rgba(168,85,247,0.02)',
            ],
            'emerald' => [
                'label' => 'Emerald',
                'accent' => '#10b981',
                'accentHover' => '#34d399',
                'accentDim' => 'rgba(16,185,129,0.10)',
                'accentBorder' => 'rgba(16,185,129,0.25)',
                'accentText' => '#047857',
                'orb1' => 'rgba(16,185,129,0.18)',
                'orb2' => 'rgba(4,120,87,0.10)',
                'orb3' => 'rgba(167,243,208,0.12)',
                'stroke' => 'rgba(52,211,153,0.55)',
                'bgFrom' => '#ecfdf5',
                'bgTo' => '#d1fae5',
                'ctaGradFrom' => 'rgba(16,185,129,0.07)',
                'ctaGradTo' => 'rgba(16,185,129,0.02)',
            ],
            'rose' => [
                'label' => 'Rose',
                'accent' => '#f43f5e',
                'accentHover' => '#fb7185',
                'accentDim' => 'rgba(244,63,94,0.10)',
                'accentBorder' => 'rgba(244,63,94,0.25)',
                'accentText' => '#be123c',
                'orb1' => 'rgba(244,63,94,0.18)',
                'orb2' => 'rgba(159,18,57,0.10)',
                'orb3' => 'rgba(253,164,175,0.12)',
                'stroke' => 'rgba(251,113,133,0.55)',
                'bgFrom' => '#fff1f2',
                'bgTo' => '#ffe4e6',
                'ctaGradFrom' => 'rgba(244,63,94,0.07)',
                'ctaGradTo' => 'rgba(244,63,94,0.02)',
            ],
            'indigo' => [
                'label' => 'Indigo',
                'accent' => '#6366f1',
                'accentHover' => '#818cf8',
                'accentDim' => 'rgba(99,102,241,0.10)',
                'accentBorder' => 'rgba(99,102,241,0.25)',
                'accentText' => '#4338ca',
                'orb1' => 'rgba(99,102,241,0.18)',
                'orb2' => 'rgba(55,48,163,0.10)',
                'orb3' => 'rgba(199,210,254,0.12)',
                'stroke' => 'rgba(129,140,248,0.55)',
                'bgFrom' => '#eef2ff',
                'bgTo' => '#e0e7ff',
                'ctaGradFrom' => 'rgba(99,102,241,0.07)',
                'ctaGradTo' => 'rgba(99,102,241,0.02)',
            ],
            'teal' => [
                'label' => 'Teal',
                'accent' => '#14b8a6',
                'accentHover' => '#2dd4bf',
                'accentDim' => 'rgba(20,184,166,0.10)',
                'accentBorder' => 'rgba(20,184,166,0.25)',
                'accentText' => '#0f766e',
                'orb1' => 'rgba(20,184,166,0.18)',
                'orb2' => 'rgba(15,118,110,0.10)',
                'orb3' => 'rgba(153,246,228,0.12)',
                'stroke' => 'rgba(45,212,191,0.55)',
                'bgFrom' => '#f0fdfa',
                'bgTo' => '#ccfbf1',
                'ctaGradFrom' => 'rgba(20,184,166,0.07)',
                'ctaGradTo' => 'rgba(20,184,166,0.02)',
            ],
            'amber' => [
                'label' => 'Amber',
                'accent' => '#f59e0b',
                'accentHover' => '#fbbf24',
                'accentDim' => 'rgba(245,158,11,0.10)',
                'accentBorder' => 'rgba(245,158,11,0.25)',
                'accentText' => '#b45309',
                'orb1' => 'rgba(245,158,11,0.18)',
                'orb2' => 'rgba(180,83,9,0.10)',
                'orb3' => 'rgba(253,230,138,0.12)',
                'stroke' => 'rgba(251,191,36,0.55)',
                'bgFrom' => '#fffbeb',
                'bgTo' => '#fef3c7',
                'ctaGradFrom' => 'rgba(245,158,11,0.07)',
                'ctaGradTo' => 'rgba(245,158,11,0.02)',
            ],
            'cyan' => [
                'label' => 'Cyan',
                'accent' => '#06b6d4',
                'accentHover' => '#22d3ee',
                'accentDim' => 'rgba(6,182,212,0.10)',
                'accentBorder' => 'rgba(6,182,212,0.25)',
                'accentText' => '#0e7490',
                'orb1' => 'rgba(6,182,212,0.18)',
                'orb2' => 'rgba(14,116,144,0.10)',
                'orb3' => 'rgba(165,243,252,0.12)',
                'stroke' => 'rgba(34,211,238,0.55)',
                'bgFrom' => '#ecfeff',
                'bgTo' => '#cffafe',
                'ctaGradFrom' => 'rgba(6,182,212,0.07)',
                'ctaGradTo' => 'rgba(6,182,212,0.02)',
            ],
        ];

        // Fallback to orange if color not found
        $theme = $themes[$color] ?? $themes['orange'];

        $allColors = array_map(fn ($k, $v) => [
            'slug' => $k,
            'label' => $v['label'],
            'hex' => $v['accent'],
        ], array_keys($themes), array_values($themes));

        return view('welcome.4', compact('theme', 'allColors', 'color'));
    }

    /**
     * Template 5: Minimal & Elegant Championship Invitation
     */
    public function template5()
    {
        return view('welcome.5');
    }
}
