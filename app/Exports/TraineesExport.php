<?php

namespace App\Exports;

use App\Models\Trainee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TraineesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
     * Return a collection of data to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Specify the fields you want to export
        return Trainee::all(['id', 'customid', 'yellow_card', 'full_name', 'ሙሉ_ስም', 'photo',  'gender', 'ጾታ', 'nationality', 'ዜግነት',  'city', 'ከተማ', 'sub_city', 'ክፍለ_ከተማ', 'woreda', 'ወረዳ', 'house_no', 'phone_no', 'po_box', 'birth_place', 'የትዉልድ_ቦታ', 'education_level', 'blood_type', 'receipt_no', 'dob', 'status']); // Adjust fields as necessary
    }

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Custom ID',
            'Yellow Card',
            'Full Name',
            'ሙሉ_ስም',
            'Photo',
            'Gender',
            'ጾታ',
            'Nationality',
            'ዜግነት',
            'City',
            'ከተማ',
            'Sub City',
            'ክፍለ_ከተማ',
            'Woreda',
            'ወረዳ',
            'House No',
            'Phone No',
            'Po Box',
            'Birth Place',
            'የትዉልድ_ቦታ',
            'Education Level',
            'Blood Type',
            'Receipt No',
            'DOB',
            'Status',
        ];
    }

/**
     * Apply styles to the worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
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
           $event->sheet->getDelegate()->getStyle('A2:Z' . $event->sheet->getHighestRow())
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