<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActiveTodayBoooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $bookings = Booking::where(['status' => 'New'])->get();

       $currentDateTime = Carbon::now()->startOfDay();

       foreach ($bookings as $booking) {
            if ($booking->department->id != 35) {//35 is Hotels and hotel apartments main department

                $bookingDateTime = Carbon::create(
                    $booking->bookingDetail->year,
                    $booking->bookingDetail->month,
                    $booking->bookingDetail->day
                )->startOfDay();

                if ($currentDateTime->eq($bookingDateTime)) {
                    $booking->update(['status' => 'Today']);
                }
            }
        }
    }
}
