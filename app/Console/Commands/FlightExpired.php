<?php

namespace App\Console\Commands;

use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FlightExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Flights:flight-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all flights';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeMinus2Hours = Carbon::parse(Carbon::now())->subRealHours(2);
        Flight::where('departure', '<', $timeMinus2Hours)->update([
            'status' => true
        ]);
        info('Updated Successfully');
    }
}
