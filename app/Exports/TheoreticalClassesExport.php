<?php

namespace App\Exports;

use App\Models\TheoreticalClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TheoreticalClassesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return TheoreticalClass::all(['trainee_name', 'trainer_name', 'class_name', 'start_date', 'end_date']);
    }

    public function headings(): array
    {
        return [
            'Trainee Name',
            'Trainer Name',
            'Class Name',
            'Start Date',
            'End Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A2:E' . $event->sheet->getHighestRow())
                    ->applyFromArray([
                        'font' => [
                            'size' => 12,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]);
            },
        ];
    }
}
