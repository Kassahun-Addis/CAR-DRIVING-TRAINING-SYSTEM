<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Bank; 
use App\Models\Student; // Assuming you still need this for some reason
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\Log;




class PaymentController extends Controller
{

    public function fetchTrainee(Request $request)
{
    $custom_id = $request->query('custom_id');

    // Fetch the trainee information including the category
    $trainee = DB::table('trainees')
        ->where('custom_id', $custom_id)
        ->select('full_name', 'tin_no', 'category')
        ->first();

    // Initialize carCategory as null
    $carCategory = null;

    // If trainee is found, fetch the car category price
    if ($trainee && $trainee->category) {
        $carCategory = DB::table('car_categories')
            ->where('car_category_name', $trainee->category)
            ->select('price') // Assuming you have a 'price' column in your car_categories table
            ->first();
    }

    return response()->json(['trainee' => $trainee, 'carCategory' => $carCategory]);
}

    public function exportPdf()
{
    $payments = Payment::all();
    $pdf = Pdf::loadView('Payment.pdf', compact('payments'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('payments_list.pdf');
}

public function exportExcel()
{
    return Excel::download(new PaymentsExport, 'payments_list.xlsx');
}
    
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        // Eager load the bank relationship
        $payments = Payment::with('bank')
            ->when($search, function ($query, $search) {
                return $query->where('full_name', 'like', "%{$search}%")
                             ->orWhere('tin_no', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return view('Payment.index', compact('payments'));
    }

    public function create()
    {
        // Fetch all banks from the database
        $banks = Bank::all();

        // Return the view with the banks variable
        return view('Payment.payment', compact('banks'));
    }

    public function store(Request $request)
{
        // Validate the incoming request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'custom_id' => 'required|string|max:5',
            'tin_no' => 'nullable|string|max:20',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Cash,Bank,Telebirr',
            'bank_id' => 'required_if:payment_method,Bank|exists:banks,id',
            'transaction_no' => 'nullable|string|max:255',
            'sub_total' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        // Calculate remaining balance
        $remainingBalance = $request->total - $request->amount_paid;

        // Determine payment status
        $paymentStatus = $remainingBalance > 0 ? 'Partial' : 'Paid';

        // Prepare payment data
        $paymentData = $request->all();
        $paymentData['remaining_balance'] = $remainingBalance;
        $paymentData['payment_status'] = $paymentStatus;

        // Set bank_id to null if payment method is not Bank
        if ($request->payment_method !== 'Bank') {
            $paymentData['bank_id'] = null;
        }
        // Create payment record
        $payment = Payment::create($paymentData);
        // Record payment history
        PaymentHistory::create([
            'payment_id' => $payment->payment_id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'transaction_no' => $request->transaction_no,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->payment_method === 'Bank' ? $request->bank_id : null,
            'payment_status' => $paymentStatus,
        ]);

   

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');

   
}


public function edit($id)
{
    // Retrieve the payment record by its ID
    $payment = Payment::findOrFail($id);

    // Retrieve the list of banks from the database
    $banks = Bank::all();

    // Return the edit view with the payment and banks data
    return view('Payment.edit', compact('payment', 'banks'));
}

public function update(Request $request, Payment $payment)
{
    \Log::info('Update method hit', ['payment_id' => $payment->payment_id]);

    // Validate incoming request data
    try {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'custom_id' => 'required|string|max:5',
            'tin_no' => 'nullable|string|max:20',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Cash,Bank,Telebirr',
            'bank_id' => 'required_if:payment_method,Bank|exists:banks,id', // Validate bank_id only if payment method is Bank
            'transaction_no' => 'nullable|string|max:255',
            'sub_total' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Paid,Partial,Unpaid', // Corrected to match 'Partial' status
            'discount' => 'nullable|numeric|min:0',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed', [
            'errors' => $e->validator->errors()->toArray()
        ]);
        return back()->withErrors($e->validator)->withInput();
    }

    // Initialize payment data array
    $paymentData = $request->all();

    // If the payment method is not Bank, unset the bank_id
    if ($request->payment_method !== 'Bank') {
        $paymentData['bank_id'] = null; // Ensure bank_id is null for non-Bank methods
    }

    // Calculate remaining balance before updating
    $remainingBalance = $request->total - $request->amount_paid;

    // Update payment
    $payment->update(array_merge($paymentData, ['remaining_balance' => $remainingBalance]));

    // Record payment history
    PaymentHistory::create([
        'payment_id' => $payment->payment_id, // Ensure this matches your Payment model primary key
        'amount_paid' => $request->amount_paid,
        'payment_date' => $request->payment_date,
        'transaction_no' => $request->transaction_no,
        'payment_method' => $request->payment_method,
        'bank_id' => $request->payment_method === 'Bank' ? $request->bank_id : null, // Store the bank ID conditionally
        'payment_status' => $remainingBalance > 0 ? 'Partial' : 'Paid',
    ]);

    return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
}




    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function print(Payment $payment)
    {
        // Fetch the trainee based on the custom_id in the payment
        $trainee = \App\Models\Trainee::where('custom_id', $payment->custom_id)->first();
    
        // Pass the trainee along with the payment to the view
        return view('Payment.print', compact('payment', 'trainee'));
    }

    public function payRemaining(Payment $payment)
    {
        // Ensure the remaining balance is greater than 0
        if ($payment->remaining_balance <= 0) {
            return redirect()->route('payments.index')->with('error', 'No remaining balance to pay.');
        }

        // Fetch the list of banks from the database
        $banks = Bank::all();

        // Pass the payment and banks data to the view
        return view('Payment.pay_remaining', compact('payment', 'banks'));
    }
    
    public function processRemainingPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:' . $payment->remaining_balance,
            'payment_date' => 'required|date',
            'transaction_no' => 'required|string',
            'payment_method' => 'required|string',
            'bank_name' => 'nullable|string',
            'payment_status' => 'required|string',
        ]);
    
        // Update the payment record
        $payment->amount_paid += $request->amount_paid;
        $payment->remaining_balance -= $request->amount_paid;
    
        // Update the payment status if balance is fully paid
        if ($payment->remaining_balance == 0) {
            $payment->payment_status = 'Paid';
        } else {
            $payment->payment_status = 'Partial';
        }
    
        $payment->save();
    
        // Record the payment history
        DB::table('payment_history')->insert([
            'payment_id' => $payment->payment_id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'transaction_no' => $request->transaction_no,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'payment_status' => $request->payment_status,
            'created_at' => now(),
        ]);
    
        return redirect()->route('payments.index')->with('success', 'Remaining balance paid and recorded successfully.');
    }
    
    public function showPaymentHistory(Payment $payment)
    {
        $paymentHistory = DB::table('payment_history')->where('payment_id', $payment->payment_id)->get();
    
        return view('Payment.payment_history', compact('payment', 'paymentHistory'));
    }
        

}

