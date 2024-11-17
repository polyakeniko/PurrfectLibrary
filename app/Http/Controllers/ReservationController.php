<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user', 'bookCopy.book')->orderBy('reservation_date', 'desc')->get();

        return view('reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,canceled,completed',
        ]);

        $reservation->status = $request->status;
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
