<?php
namespace App\Services;

use App\Models\Provider;
use App\Models\Schedule;

class ScheduleService
{
    public function getProviderWorkTime($providerId)
    {
        $data =[];
        $provider_work_all_time = Provider::find($providerId)->open_all_time ;
        $providerSchedules = Schedule::where('provider_id', $providerId)->get();

        // Check if the schedules have the same day time
        if (Schedule::hasSameDayTime($providerSchedules) && !$provider_work_all_time) {
            // All days have the same start and end time
            $data =['start_time' => $providerSchedules->first()->start_time,
                    'end_time' =>$providerSchedules->first()->end_time];
            $result = [
                'open_all_time' => $provider_work_all_time ? true : false,
                'same_day_time' => true,
                'data' => $data
            ];
        }else {
            // Each day has a different start and end time
            $result = [];
            foreach ($providerSchedules as $schedule) {
                $data[$schedule->day_of_week] = [
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time
                ];
            }

            $result['open_all_time'] =  $provider_work_all_time ? true : false;
            $result['same_day_time'] = false;
            $result['data'] = $data;
        }



        return $result;
    }
}
