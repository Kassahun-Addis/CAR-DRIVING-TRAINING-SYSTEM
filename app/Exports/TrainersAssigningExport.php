<?php

namespace App\Exports;

use App\Models\TrainerAssigning;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TrainersAssigningExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return TrainerAssigning::all(['trainee_name', 'trainer_name', 'start_date', 'end_date', 'category_id', 'car_name', 'plate_no', 'total_time']);
    }

    public function headings(): array
    {
        return [
            'Trainee Name',
            'Trainer Name',
            'Start Date',
            'End Date',
            'Category',
            'Car Name',
            'Plate No',
            'Total Time',
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
                $event->sheet->getDelegate()->getStyle('A2:H' . $event->sheet->getHighestRow())
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