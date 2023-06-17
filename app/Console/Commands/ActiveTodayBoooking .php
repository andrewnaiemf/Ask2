<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireBoooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:boooking';

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
            $bookingDateTime = Carbon::create(
                $booking->year,
                $booking->month,
                $booking->day
            )->startOfDay();

            if ($currentDateTime->eq($bookingDateTime)) {
                $booking->update(['status' => 'Today']);
            }
        }
    }
}