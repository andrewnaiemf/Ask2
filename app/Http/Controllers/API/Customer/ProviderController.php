<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Models\Provider;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function show($id)
    {
        $provider = Provider::where('id', $id)
        ->where('status', 'Accepted')
        ->with('department', 'subdepartment', 'images', 'ratings', 'user')
        ->first();

        if (!$provider) {
           return $this->returnError(trans('api.InvalidProvider'));
        }

        if ($provider->subdepartment->id == 22) {
            $clinicIds = Clinic::pluck('id');
            $clinicsData = ClinicSchedule::whereIn('clinic_id',$clinicIds)->with('clinic','clinicScheduleDoctors')->get();

            $provider['clinic_scedules'] = $clinicsData;
        }else{
            $provider['clinic_scedules'] =[];
        }


        $scheduleService = new ScheduleService();
        $workTime = $scheduleService->getProviderWorkTime($provider->id);

        $provider['schedule'] = $workTime;

        return $this->returnData(['provider' => $provider ]);
    }
}