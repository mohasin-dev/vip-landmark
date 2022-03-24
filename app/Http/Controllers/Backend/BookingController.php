<?php

namespace App\Http\Controllers\Backend;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $booking = Booking::query();

        if ($request->searchKeyword) {
            $booking->where('name',  'LIKE', "%$request->searchKeyword%")
                    ->orWhere('email',  'LIKE', "%$request->searchKeyword%")
                    ->orWhere('contact_number',  'LIKE', "%$request->searchKeyword%");
        }

        $booking = $booking->orderBy('name')->paginate(10);

        return view('backend.booking.index', compact('booking'));
    }

    /**
     * Update the visitor feedback status in reverse.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Booking $booking)
    {
        $status = $booking->status;
        $booking->status = $status;
        $booking->save();

        Alert::toast('Status successfully updated', 'success');

        return redirect()->back();
    }
}
