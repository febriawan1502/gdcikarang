<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MaterialExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Material::with(['creator', 'updater'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            // Kolom dari format export.XLSX yang ada
            'Nomor',
            'Company Code',
            'Company Code Description',
            'Plant',
            'Plant Description',
            'Storage Location',
            'Storage Location Description',
            'Material Type',
            'Material Type Description',
            'Material Code',
            'Material Description',
            'Material Group',
            'Base Unit of Measure',
            'Valuation Type',
            'Unrestricted Use Stock',
            'Quality Inspection Stock',
            'Blocked Stock',
            'In Transit Stock',
            'Project Stock',
            'WBS Element',
            'Valuation Class',
            'Valuation Description',
            
            // Kolom tambahan dari database yang tidak ada di format asli
            'Harga Satuan',
            'Total Harga',
            'Currency',
            'Pabrikan',
            'Normalisasi',
            'Qty',
            'Tanggal Terima',
            'Keterangan',
            'Status',
            'Rak',
            'Created At',
            'Updated At',
            'Created By',
            'Updated By'
        ];
    }

    /**
     * @param Material $material
     * @return array
     */
    public function map($material): array
    {
        return [
            // Data sesuai format export.XLSX yang ada
            $material->nomor,
            $material->company_code,
            $material->company_code_description,
            $material->plant,
            $material->plant_description,
            $material->storage_location,
            $material->storage_location_description,
            $material->material_type,
            $material->material_type_description,
            $material->material_code,
            $material->material_description,
            $material->material_group,
            $material->base_unit_of_measure,
            $material->valuation_type,
            $material->unrestricted_use_stock,
            $material->quality_inspection_stock,
            $material->blocked_stock,
            $material->in_transit_stock,
            $material->project_stock,
            $material->wbs_element,
            $material->valuation_class,
            $material->valuation_description,
            
            // Data tambahan dari database
            $material->harga_satuan,
            $material->total_harga,
            $material->currency,
            $material->pabrikan,
            $material->normalisasi,
            $material->qty,
            $material->tanggal_terima ? $material->tanggal_terima->format('Y-m-d') : '',
            $material->keterangan,
            $material->status,
            $material->rak,
            $material->created_at ? $material->created_at->format('Y-m-d H:i:s') : '',
            $material->updated_at ? $material->updated_at->format('Y-m-d H:i:s') : '',
            $material->creator ? $material->creator->name : '',
            $material->updater ? $material->updater->name : ''
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:AJ1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style untuk semua data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:AJ' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}