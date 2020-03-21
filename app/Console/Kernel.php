<?php

namespace App\Console;

use App\Device;
use App\Feedback;
use App\Wishlist;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $client = new \GuzzleHttp\Client();
            $url = "https://onesignal.com/api/v1/notifications";


            $wishlists = Wishlist::all();
            foreach ($wishlists as $wishlist) {
                $feedbackValue = Feedback::where('item_group_id', $wishlist->item_group_id)
                    ->orderBy('created_at', 'desc')
                    ->take(1)
                    ->get()[0]->value;

                if ($feedbackValue === 'true') {
                    foreach (Device::all() as $device) {
                        $request = $client->post($url,
                            [
                                'headers'         => ['Content-Type' => 'application/json'],
                                'body' => json_encode([
                                    'include_player_ids' => $device->device_token,
                                    'app_id' => '12'
                                ])
                            ]
                        );
                        $response = $request->send();
                        dd($response);
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
