<?php

namespace App\Http\Controllers\API\Provider;
use App\Http\Controllers\Controller;
use App\Models\ClinicSchedule;
use App\Models\clinicScheduleDoctor;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClinicSceduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validatescheduleData($request);

        $provider = Provider::where('user_id',auth()->user()->id)->first();
        $clinicId = $request->clinic_id;

        $existingSchedules = ClinicSchedule::where('provider_id', $provider->id)
            ->where('clinic_id', $clinicId)
            ->get();

        foreach ($request->schedule as $scheduleData) {
            $dayOfWeek = $scheduleData['day_of_week'];

            $clinicSchedule = $existingSchedules->firstWhere('day_of_week', $dayOfWeek);

            if (!$clinicSchedule) {
                $clinicSchedule = ClinicSchedule::create([
                    'provider_id' => $provider->id,
                    'clinic_id' => $clinicId,
                    'day_of_week' => $dayOfWeek,
                ]);
            }
            $clinicSchedule->update(['is_open'=>$scheduleData['is_open']]);
            $doctorsData = $scheduleData['doctors'];

            // Get the doctor names from the request
            $doctorNames = collect($doctorsData)->pluck('name');

            // Delete the clinic schedule doctors that are not in the request
            $clinicSchedule->clinicScheduleDoctors()
                ->whereNotIn('doctor_name', $doctorNames)
                ->delete();

            // Update or create the clinic schedule doctors
            foreach ($scheduleData['doctors'] as $doctorData) {
                $clinicSchedule->clinicScheduleDoctors()->updateOrCreate(
                    ['doctor_name' => $doctorData['name']],
                    [
                        'doctor_cost' => $doctorData['cost'],
                        'start_time' => $doctorData['start_time'],
                        'end_time' => $doctorData['end_time'],
                    ]
                );
            }
        }

        return $this->returnSuccessMessage( trans("api.clinic.scheduleUpdatedSuccessfully") );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = Provider::where(['user_id' => auth()->user()->id])->first();

        $schedules = ClinicSchedule::where(['clinic_id' => $id, 'provider_id' => $provider->id])->with('clinicScheduleDoctors')->get();

        return $this->returnData( $schedules);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validatescheduleData ( $request ) {

        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required|numeric',
            'schedule' => 'required|array',
            'schedule.*.day_of_week' => 'required|numeric',
            'schedule.*.doctors.*.start_time' => 'nullable|date_format:H:i',
            'schedule.*.doctors.*.end_time' => 'nullable|date_format:H:i',
            'schedule.*.doctors' => 'nullable|array',
            'schedule.*.doctors.*.name' => 'nullable|string',
            'schedule.*.doctors.*.cost' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

    }

}
