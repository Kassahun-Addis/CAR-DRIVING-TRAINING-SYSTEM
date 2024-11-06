<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Models\Trainee;
use App\Models\Payment;
use App\Models\Trainer;
use App\Models\TheoreticalClass;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        // Log the incoming request data
        \Log::info('Request Data:', $request->all());

        $recordType = $request->input('record_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentStatus = $request->input('payment_status_option');

        $data = [];

        switch ($recordType) {
            case 'trainees':
                $gender = $request->input('gender_option');
                $category = $request->input('category_option');
                $educationLevel = $request->input('education_level_option');
                $bloodType = $request->input('blood_type_option');

                $data = Trainee::query()
                    ->when($startDate, fn($query) => $query->where('created_at', '>=', $startDate))
                    ->when($endDate, fn($query) => $query->where('created_at', '<=', $endDate))
                    ->when($gender && $gender !== 'all', fn($query) => $query->where('gender', $gender))
                    ->when($category, fn($query) => $query->where('category', $category))
                    ->when($educationLevel, fn($query) => $query->where('education_level', $educationLevel))
                    ->when($bloodType, fn($query) => $query->where('blood_type', $bloodType))
                    ->get();

                \Log::info('Trainee Data:', $data->toArray());

                $view = 'reports.trainee_report';
                break;

            case 'payments':
                $data = Payment::query()
                    ->when($startDate, fn($query) => $query->where('payment_date', '>=', $startDate))
                    ->when($endDate, fn($query) => $query->where('payment_date', '<=', $endDate))
                    ->when($paymentStatus, fn($query) => $query->where('payment_status', $paymentStatus))
                    ->get();

                \Log::info('Payment Data:', $data->toArray());

                $view = 'reports.payment_report';
                break;

            case 'trainers':
                $status = $request->input('status_option');
                $gender = $request->input('gender_option');
                $data = Trainer::query()
                    ->when($startDate, fn($query) => $query->where('created_at', '>=', $startDate))
                    ->when($endDate, fn($query) => $query->where('created_at', '<=', $endDate))
                    ->when($status, fn($query) => $query->where('status', $status))
                    ->when($gender && $gender !== 'all', fn($query) => $query->where('gender', $gender))
                    ->get();

                \Log::info('Trainer Data:', $data->toArray());

                $view = 'reports.trainer_report';
                break;
                case 'classes':
                    $selectedClass = $request->input('classes_option');
                    $data = TheoreticalClass::query()
                        ->when($startDate, fn($query) => $query->where('created_at', '>=', $startDate))
                        ->when($endDate, fn($query) => $query->where('created_at', '<=', $endDate))
                        ->when($selectedClass, fn($query) => $query->where('class_name', $selectedClass)) // Assuming 'class_name' is the column name
                        ->get();
    
                \Log::info('Class Data:', $data->toArray());
    
                $view = 'reports.class_report';
                break;
            default:
                return redirect()->route('reports.index')->with('error', 'Invalid record type selected.');
        }

        if ($data->isEmpty()) {
            return redirect()->route('reports.index')->with('error', 'No data found for the selected criteria.');
        }

        $html = view($view, compact('data'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            'default_font' => 'Nyala',
            'fontDir' => [base_path('public/fonts')],
            'fontdata' => [
                'nyala' => [
                    'R' => 'nyala.ttf',
                ],
            ],
            'default_font_size' => 10,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output("{$recordType}_report.pdf", 'D');
    }
}