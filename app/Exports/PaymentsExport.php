<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Payment::with('bank')
            ->get(['payment_id', 'full_name', 'tin_no', 'payment_date', 'payment_method', 'bank_id', 'transaction_no', 'sub_total', 'vat', 'total', 'amount_paid', 'remaining_balance', 'payment_status'])
            ->map(function ($payment) {
                return [
                    'NO' => $payment->payment_id,
                    'Full Name' => $payment->full_name,
                    'TIN No' => $payment->tin_no,
                    'Payment Date' => $payment->payment_date,
                    'Payment Method' => $payment->payment_method,
                    'Bank Name' => $payment->bank->bank_name ?? '', // Assuming 'name' is the column for bank name
                    'Transaction No' => $payment->transaction_no,
                    'Sub Total' => $payment->sub_total,
                    'VAT' => $payment->vat,
                    'Total' => $payment->total,
                    'Paid Amount' => $payment->amount_paid,
                    'Remaining Balance' => $payment->remaining_balance,
                    'Payment Status' => $payment->payment_status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'NO',
            'Full Name',
            'TIN No',
            'Payment Date',
            'Payment Method',
            'Bank Name',
            'Transaction No',
            'Sub Total',
            'VAT',
            'Total',
            'Paid Amount',
            'Remaining Balance',
            'Payment Status',
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
