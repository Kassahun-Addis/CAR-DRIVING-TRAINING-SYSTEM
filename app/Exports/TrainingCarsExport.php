<?php

namespace App\Exports;

use App\Models\TrainingCar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class TrainingCarsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return TrainingCar::all(['id', 'name', 'category', 'model', 'year', 'plate_no']);
    }

    public function headings(): array
    {
        return [
            'No',
            'Vehicle Name',
            'Category',
            'Model',
            'Year',
            'Plate No',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'name' => 'Nyala',
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }


    /**
* Register events to apply styles to data rows.
*
* @return array
*/
public function registerEvents(): array
{
   return [
       AfterSheet::class => function(AfterSheet $event) {
           $event->sheet->getDelegate()->getStyle('A2:F' . $event->sheet->getHighestRow())
               ->applyFromArray([
                   'font' => [
                       'name' => 'Nyala',
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

