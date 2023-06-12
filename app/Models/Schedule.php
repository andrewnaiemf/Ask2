<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'provider_id', 'day_of_week', 'start_time', 'end_time', 'is_active'
    ];

    protected $table = 'provider_schedule';

	protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at'
	];

    public function toArray()
    {
        $scheduleArray = parent::toArray();

        $scheduleArray['open_all_time'] = $this->provider()->first()->open_all_time;

        return $scheduleArray;
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public static function hasSameDayTime($schedules)
    {
        if ($schedules->count() === 0) {
            return false;
        }


        $firstDay = $schedules->first();

        // Iterate over the remaining days in the schedule
        foreach ($schedules as $day) {
            // Compare the start and end times of the current day with the first day
            if ($day->start_time !== $firstDay->start_time || $day->end_time !== $firstDay->end_time ) {
                // If the start or end time is different, return false
                return false;
            }
        }

        // All days have the same start and end time, return true
        return true;
    }


}
