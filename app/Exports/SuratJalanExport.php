<?php

namespace App\Exports;

use App\Models\SuratJalan;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class SuratJalanExport implements FromView, WithStyles
{
    protected $suratJalan;

    public function __construct(SuratJalan $suratJalan)
    {
        $this->suratJalan = $suratJalan;
    }

    public function view(): View
    {
        return view('exports.surat-jalan', [
            'suratJalan' => $this->suratJalan
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
            
            // Style the header row
            'A1:F1' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE2E2E2']
                ]
            ]
        ];
    }
}
