<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapitulasiEmbuExport implements FromView, ShouldAutoSize
{
    protected $registrations;

    protected $metadata;

    public function __construct($registrations, $metadata)
    {
        $this->registrations = $registrations;
        $this->metadata = $metadata;
    }

    public function view(): View
    {
        return view('exports.rekapitulasi_embu', [
            'registrations' => $this->registrations,
            'metadata' => $this->metadata,
        ]);
    }
}
