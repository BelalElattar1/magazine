<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {

    Subscription::where('status', 'active')->whereDate('end', '<',  Carbon::now()->toDateString())->update([
        'status' => 'expire'
    ]);

    $subscriptions = Subscription::where('status', 'active')->whereDate('end', '=',  Carbon::now()->addDays(5))->get();
    if($subscriptions) {

        foreach($subscriptions as $subscription) {
            $data = [
                'subscriber_name' => $subscription->subscriber->name,
                'magazine_name'   => $subscription->magazine->name
            ];
            Mail::to($subscription->subscriber->email)->send(new SendEmail($data));
        }

    }
 
})->dailyAt('22:09');