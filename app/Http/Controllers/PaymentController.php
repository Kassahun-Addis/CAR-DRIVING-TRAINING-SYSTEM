<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Bank; 
use App\Models\Student; // Assuming you still need this for some reason
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
        public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        $payments = Payment::when($search, function ($query, $search) {
                return $query->where('FullName', 'like', "%{$search}%")
                            ->orWhere('TinNo', 'like', "%{$search}%");
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
        $request->validate([
            'FullName' => 'required|string|max:255',
            'TinNo' => 'required|string|max:20',
            'PaymentDate' => 'required|date',
            'PaymentMethod' => 'required|in:Cash,Bank,Telebirr',
            'BankID' => 'required_if:PaymentMethod,Bank|exists:banks,id', // Validate BankID conditionally
            'TransactionNo' => 'nullable|string|max:255',
            'SubTotal' => 'required|numeric|min:0',
            'Vat' => 'required|numeric|min:0',
            'Total' => 'required|numeric|min:0',
            'PaymentStatus' => 'required|in:Paid,Pending,Overdue',
        ]);
    
        // Create payment, ensuring to include only the required fields
        Payment::create($request->all());
    
        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }



    public function edit(Payment $payment)
    {
        return view('Payment.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'FullName' => 'required|string|max:255',
            'TinNo' => 'required|string|max:20', // Assuming TIN has a max length
            'PaymentDate' => 'required|date',
            'PaymentMethod' => 'required|in:Cash,Bank,Telebirr',
            'BankName' => 'nullable|string|max:255',
            'TransactionNo' => 'nullable|string|max:255',
            'SubTotal' => 'required|numeric|min:0',
            'Vat' => 'required|numeric|min:0',
            'Total' => 'required|numeric|min:0',
            'PaymentStatus' => 'required|in:Paid,Pending,Overdue',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function print(Payment $payment)
    {
        // Fetch the trainee based on the customid in the payment
        $trainee = \App\Models\Trainee::where('customid', $payment->customid)->first();
    
        // Pass the trainee along with the payment to the view
        return view('Payment.print', compact('payment', 'trainee'));
    }


}