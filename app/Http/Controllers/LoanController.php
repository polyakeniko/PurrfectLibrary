<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::whereNull('returned_date')->with('user', 'bookCopy')->get();

        return view('loans.index', compact('loans'));
    }

    public function markAsReturned(Loan $loan)
    {
        $loan->returned_date = Carbon::today();

        if ($loan->return_due_date && Carbon::parse($loan->returned_date)->greaterThan($loan->return_due_date)) {
            $lateDays = Carbon::parse($loan->returned_date)->diffInDays($loan->return_due_date);
            $loan->late_fee = $lateDays * 1.00;
        } else {
            $loan->late_fee = 0;
        }

        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Loan marked as returned.');
    }
}
