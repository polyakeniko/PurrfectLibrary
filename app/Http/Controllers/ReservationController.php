<?php

namespace App\Http\Controllers;

use App\Mail\ReservationStatusChanged;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with('user', 'bookCopy.book');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('bookCopy.book', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'ready', 'canceled', 'completed'])) {
                $query->where('status', $status);
            }
        }

        $reservations = $query->orderBy('reservation_date', 'desc')->get();

        return view('reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,canceled,completed',
        ]);

        if ($reservation->status === 'completed' && $request->status !== 'completed') {
            Loan::where('user_id', $reservation->user_id)
                ->where('book_copy_id', $reservation->book_copy_id)
                ->delete();
        }

        $reservation->status = $request->status;
        $reservation->save();

        if ($request->status === 'completed') {
            Loan::create([
                'user_id' => $reservation->user_id,
                'book_copy_id' => $reservation->book_copy_id,
                'return_due_date' => now()->addWeeks(2), // Set the due date to 2 weeks from now
            ]);
        }

        if (in_array($request->status, ['ready', 'canceled'])) {
            Mail::to($reservation->user->email)->send(new ReservationStatusChanged($reservation, $request->status));
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
