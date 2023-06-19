<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Clinic;
use App\Models\ClinicBooking;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                ->with(['subdepartment','clinicBookings'])
                ->orderBy('id', 'desc')
                ->simplePaginate($perPage);

        return $this->returnData($bookings);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        $booking->load((['provider','user','subdepartment','clinicBookings.clinic']));

        return $this->returnData($booking);
    }

    public function store(Request $request){

        $validation =  $this->validateAddressData( $request );

        if ( $validation) {
            return $validation;
        }

        $provider = Provider::find($request->provider_id);

        $data = [
            'department_id'=> $provider->department->id,
            'sub_department_id'=> $provider->subDepartment->id,
            'user_id' => auth()->user()->id
        ];

        $data = array_merge($data , $request->all());

        $booking = Booking::create( $data);

        if ($request['clinic_id']) {
            $clinic = Clinic::find($request['clinic_id']);
            if ($clinic) {
                $clinic_boooking = ClinicBooking::create([
                    'booking_id' => $booking->id,
                    'doctor_name' => $request->doctor_name,
                    'cost' => $request->cost,
                    'clinic_id' => $clinic->id,
                ]);
            }
        }


        return $this->returnSuccessMessage( trans("api.bookingSentSuccessfully") );
    }


    public function validateAddressData ( $request ) {

        $validator = Validator::make($request->all(), [

            'provider_id' => 'required|exists:providers,id',
            'note' => 'nullable|string',
            'year' => 'required|integer|date_format:Y|in:'.date('Y'),
            // 'month' => 'required|digits:2|integer|between:1,12',
            // 'day' => 'required|integer|between:1,31',
            'time' => 'required|date_format:H:i',
            'month' => [
                'required',
                'digits:2',
                'between:01,12',
                function ($attribute, $value, $fail) {
                    $currentMonth = date('m');
                    if ($value < $currentMonth || $value > $currentMonth + 1) {
                        $fail('The '.$attribute.' must be the current month or the next month.');
                    }
                }
            ],
            'day' => [
                'required',
                'integer',
                'between:01,31',
                function ($attribute, $value, $fail) {
                    $currentDay = date('d');
                    if ($value < $currentDay) {
                        $fail('The '.$attribute.' must be today or a future date.');
                    }
                }
            ],

        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
    }

}
