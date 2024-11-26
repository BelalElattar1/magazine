<?php

namespace App\Http\Controllers;

use App\ResponseTrait;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    use ResponseTrait;

    public function index() {

        if(auth()->user()->role == 'admin') {
        
            $activities =  Activity::all();
            return $this->response(message: 'Show All Activities', data: $activities);

        } else {

            return $this->response(message: 'Sorry You Are Not Admin');

        }

    }
}
