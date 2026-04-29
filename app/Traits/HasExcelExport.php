<?php

namespace App\Traits;

use App\Exports\GenericArrayExport;
use Maatwebsite\Excel\Facades\Excel;

trait HasExcelExport
{
    protected function downloadExcel($data, $headings, $filename, $title = 'Laporan')
    {
        return Excel::download(
            new GenericArrayExport($data, $headings, $title),
            $filename.'_'.now()->format('Ymd_His').'.xlsx'
        );
    }
}
