<?php

namespace App\Exports;

use App\Models\MaterialMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MaterialMasukExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    public function collection()
    {
        return MaterialMasuk::with(['details.material', 'creator'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
    }

    public function map($row): array
    {
        $materials = $row->details->map(function ($d) {
            return $d->material->material_description
                . ' (' . $d->quantity . ' ' . $d->satuan . ')';
        })->implode(', ');

        return [
            $row->tanggal_masuk
                ? date('d-m-Y', strtotime($row->tanggal_masuk))
                : '-',
            $row->nomor_kr,
            $row->pabrikan,
            $materials,
            $row->details->sum('quantity'),
            $row->creator->nama ?? '-',
            $row->status_sap,
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Masuk',
            'Nomor KR',
            'Pabrikan',
            'Material',
            'Total Qty',
            'Dibuat Oleh',
            'Status SAP',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // HEADER STYLE
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'], // biru
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // WRAP TEXT KOLOM MATERIAL
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);

        return [];
    }
}
