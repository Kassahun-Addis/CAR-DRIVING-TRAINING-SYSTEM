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

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch the trainee information including the category
        $trainee = DB::table('trainees')
            ->where('custom_id', $custom_id)
            ->where('company_id', $companyId)
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
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch payments specific to the current company
        $payments = Payment::where('company_id', $companyId)->get();
        $pdf = Pdf::loadView('Payment.pdf', compact('payments'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('payments_list.pdf');
    }

    public function exportExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch payments specific to the current company
        $payments = Payment::where('company_id', $companyId)->get();

        return Excel::download(new PaymentsExport($payments), 'payments_list.xlsx');
    }
    
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $perPage = $request->get('perPage', 10);  // Optional pagination control
        $search = $request->get('search');

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Prepare payments query with eager loading for relationships
        $payments = Payment::with('bank')->where('company_id', $companyId);
        
        // Apply search filter
        if ($search) {
            $searchUnformatted = str_replace([',', '.00'], '', $search);
            $payments->where(function ($query) use ($search, $searchUnformatted) {
                $query->where('full_name', 'like', "%{$search}%")
                      ->orWhere('tin_no', 'like', "%{$search}%")
                      ->orWhere('custom_id', 'like', "%{$search}%")
                      ->orWhere('transaction_no', 'like', "%{$search}%")
                      ->orWhere('payment_method', 'like', "%{$search}%")
                      ->orWhere('payment_status', 'like', "%{$search}%")
                      ->orWhere('sub_total', 'like', "%{$searchUnformatted}%")
                      ->orWhere('vat', 'like', "%{$searchUnformatted}%")
                      ->orWhere('total', 'like', "%{$searchUnformatted}%")
                      ->orWhere('amount_paid', 'like', "%{$searchUnformatted}%")
                      ->orWhere('discount', 'like', "%{$searchUnformatted}%");
            });
        }
        
        // Apply filter based on the `filter` query parameter
        if ($filter === 'amount_paid') {
            $payments->where('amount_paid', '>', 0);
        } elseif ($filter === 'remaining_balance') {
            $payments->where('remaining_balance', '>', 0);
        }

        // Paginate results
        $payments = $payments->paginate($perPage);
        
        // Return view with payments data
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
            'amount_paid' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Set amount_paid to 0 if it's null
        $amountPaid = $request->amount_paid ?? 0;

        // Calculate remaining balance
        $remainingBalance = $request->total - $amountPaid;

        // Determine payment status
        $paymentStatus = $remainingBalance > 0 ? 'Partial' : 'Paid';

        // Prepare payment data
        $paymentData = $request->all();
        $paymentData['remaining_balance'] = $remainingBalance;
        $paymentData['payment_status'] = $paymentStatus;
        $paymentData['company_id'] = $companyId; // Set company_id

        // Retrieve bank name if payment method is Bank
        if ($request->payment_method === 'Bank') {
            $bank = Bank::find($request->bank_id);
            $paymentData['bank_name'] = $bank ? $bank->bank_name : null;
        } else {
            $paymentData['bank_name'] = null;
        }

        // Create payment record
        $payment = Payment::create($paymentData);

        // Record payment history
        PaymentHistory::create([
            'payment_id' => $payment->payment_id,
            'amount_paid' => $amountPaid,
            'payment_date' => $request->payment_date,
            'transaction_no' => $request->transaction_no,
            'payment_method' => $request->payment_method,
            'bank_name' => $paymentData['bank_name'],
            'payment_status' => $paymentStatus,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function edit($id)
    {
        // Retrieve the payment record by its ID
        $payment = Payment::findOrFail($id);

        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

        // Retrieve the list of banks from the database
        $banks = Bank::all();

        // Return the edit view with the payment and banks data
        return view('Payment.edit', compact('payment', 'banks'));
    }

    public function update(Request $request, Payment $payment)
    {
        \Log::info('Update method hit', ['payment_id' => $payment->payment_id]);

        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

        // Validate incoming request data
        try {
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
                'amount_paid' => 'nullable|numeric|min:0',
                'payment_status' => 'required|in:Paid,Partial,Unpaid',
                'discount' => 'nullable|numeric|min:0',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->validator->errors()->toArray()
            ]);
            return back()->withErrors($e->validator)->withInput();
        }

        $amountPaid = $request->amount_paid ?? 0;

        // Initialize payment data array
        $paymentData = $request->all();

        // Retrieve bank name if payment method is Bank
        if ($request->payment_method === 'Bank') {
            $bank = Bank::find($request->bank_id);
            $paymentData['bank_name'] = $bank ? $bank->bank_name : null;
        } else {
            $paymentData['bank_name'] = null;
        }

        // Calculate remaining balance before updating
        $remainingBalance = $request->total - $amountPaid;

        // Update payment
        $payment->update(array_merge($paymentData, ['remaining_balance' => $remainingBalance]));

        // Record payment history
        PaymentHistory::create([
            'payment_id' => $payment->payment_id,
            'amount_paid' => $amountPaid,
            'payment_date' => $request->payment_date,
            'transaction_no' => $request->transaction_no,
            'payment_method' => $request->payment_method,
            'bank_name' => $paymentData['bank_name'],
            'payment_status' => $remainingBalance > 0 ? 'Partial' : 'Paid',
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function print(Payment $payment)
    {
        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

        // Fetch the trainee based on the custom_id in the payment
        $trainee = \App\Models\Trainee::where('custom_id', $payment->custom_id)->first();
    
        // Pass the trainee along with the payment to the view
        return view('Payment.print', compact('payment', 'trainee'));
    }

    public function payRemaining(Payment $payment)
    {
        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

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
        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

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
        // Ensure the payment belongs to the current company
        $this->authorizeCompany($payment);

        $paymentHistory = DB::table('payment_history')->where('payment_id', $payment->payment_id)->get();
    
        return view('Payment.payment_history', compact('payment', 'paymentHistory'));
    }

    private function authorizeCompany(Payment $payment)
    {
        $companyId = app('currentCompanyId');
        if ($payment->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}