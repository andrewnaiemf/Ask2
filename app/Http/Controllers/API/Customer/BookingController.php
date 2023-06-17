<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);

        $bookings =User::find(auth()->user()->id)->bookings()
                ->when($request->status == 'New', function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->unless($request->status == 'New', function ($query) {
                    return $query->whereNotIn('status', ['New']);
                })
                ->with(['subdepartment'])
                ->orderBy('id', 'desc')
                ->simplePaginate($perPage);

        return $this->returnData($bookings);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        $booking->load((['provider','user','clinicBookings.clinic']));

        return $this->returnData($booking);
    }
}
