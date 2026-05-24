<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Property $property)
    {
        abort_if($property->status !== 'disewakan', 404);
        abort_if(!$property->is_approved, 404);
        $bookedDates = $property->bookedDates();
        return view('pembeli.bookings.create', compact('property', 'bookedDates'));
    }

    public function store(Request $request, Property $property)
    {
        abort_if($property->status !== 'disewakan', 404);

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
            'period'     => 'required|in:monthly,yearly',
            'notes'      => 'nullable|string|max:500',
        ]);

        if (!Booking::isAvailable($property->id, $validated['start_date'], $validated['end_date'])) {
            return back()->withErrors(['start_date' => 'Tanggal yang dipilih sudah dipesan.'])->withInput();
        }

        $totalPrice = Booking::calculatePrice($property, $validated['period'], $validated['start_date'], $validated['end_date']);

        $booking = Booking::create([
            'property_id' => $property->id,
            'user_id'     => auth()->id(),
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'period'      => $validated['period'],
            'total_price' => $totalPrice,
            'deposit_paid'=> $property->deposit ?? 0,
            'notes'       => $validated['notes'],
            'status'      => 'pending',
        ]);

        return redirect()->route('pembeli.bookings.show', $booking)
            ->with('success', 'Booking berhasil! Lanjutkan ke pembayaran.');
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        $booking->load('property.cover');
        return view('pembeli.bookings.show', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('property.cover')
            ->latest()->paginate(10);
        return view('pembeli.bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(!in_array($booking->status, ['pending']), 403);
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
