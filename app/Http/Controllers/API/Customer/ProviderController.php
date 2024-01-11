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
        ->with(['department', 'subdepartment', 'images', 'ratings', 'user', 'hotelSchedule',
        'products' => function ($query) {
            $query->notDeletedCategory()->with('category.addons');
        }
        ])->first();

        if (!$provider) {
           return $this->returnError(trans('api.InvalidProvider'));
        }

        if (in_array($provider->subdepartment->id, ['22', '23', '24'])) {
            $provider->load(['clinics' => function ($query) use ($id) {
                $query->whereHas('schedules', function ($query) use ($id) {
                    $query->where('provider_id', $id);
                })->with(['schedules' => function ($query) use ($id) {
                    $query->where('provider_id', $id)->with('clinicScheduleDoctors');
                }]);
            }]);
        } else {
            $provider['clinics'] = [];
        }

        $scheduleService = new ScheduleService();
        $workTime = $scheduleService->getProviderWorkTime($provider->id);

        $provider['schedule'] = $workTime;

        return $this->returnData(['provider' => $provider ]);
    }
}
