<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role', 'user')->count();
        $totalLoans = Loan::count();
        $totalReservations = Reservation::count();
        $overdueLoans = Loan::where('return_due_date', '<', now())->count();

        return view('admin.statistics', compact('totalBooks', 'totalMembers', 'totalLoans', 'totalReservations', 'overdueLoans'));
    }
}
