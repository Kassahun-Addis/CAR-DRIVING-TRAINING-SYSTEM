<?php
namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\Trainer;
use App\Models\Payment;
use App\Models\TrainerAssigning;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        // Assuming there’s only one company record
        $company = Company::first();

        // Fetch data from the database
        $totalTrainees = Trainee::count();
        $totalTrainers = Trainer::count();
        $totalAmountPaid = Payment::sum('amount_paid');
        $totalRemainingBalance = Payment::sum('remaining_balance');

        // Fetch the number of trainees registered each month
        $traineesByMonth = Trainee::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

    // Fill in missing months with zero
    $monthlyTrainees = array_fill(1, 12, 0);
    foreach ($traineesByMonth as $month => $count) {
        $monthlyTrainees[$month] = $count;
    }

    // Fetch the number of trainer assignments grouped by category
    $assignmentsByCategory = TrainerAssigning::select(
        'category_id',
        DB::raw('COUNT(*) as count')
    )
    ->groupBy('category_id')
    ->get()
    ->pluck('count', 'category_id')
    ->toArray();

        // Pass data to the view
        return view('welcome', compact('assignmentsByCategory', 'company', 'totalTrainees', 'totalTrainers', 'totalAmountPaid', 'totalRemainingBalance', 'monthlyTrainees'));
    }
}