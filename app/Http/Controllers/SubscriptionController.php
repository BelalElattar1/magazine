<?php

namespace App\Http\Controllers;

use App\ResponseTrait;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use ResponseTrait;

    public function index() {

        if(auth()->user()->role == 'subscriber') {

            $subscriptions = Subscription::with('magazine')->where('subscriber_id', auth()->user()->id)->get();
            if($subscriptions) {

                return $this->response(message: 'Show All Subscribtions Suc', data: SubscriptionResource::collection($subscriptions));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif(auth()->user()->role == 'admin') {

            $subscriptions = Subscription::with('magazine')->get();
            if($subscriptions) {

                return $this->response(message: 'Show All Subscribtions Suc', data: SubscriptionResource::collection($subscriptions));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } 

    }

    public function store(Request $request) {

        if(auth()->user()->role == 'subscriber') {

            $validator = Validator::make($request->all(), [
                'type' => ['required', 'in:monthly,yearly'],
                'magazine_id' => ['required', 'integer', 'exists:magazines,id']
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $subscription = Subscription::where('subscriber_id', auth()->user()->id)->where('magazine_id', $request->magazine_id)->first();
            if($subscription) {

                if($subscription->status == 'expire') {

                    Subscription::create([
                        'type'         => $request->type,
                        'magazine_id'  => $request->magazine_id,
                        'subscriber_id' => auth()->user()->id
                    ]);

                    return $this->response(message: 'You Have Successfully Subscribed');

                } else {

                    return $this->response(message: 'You Have Already Subscribed');
    
                }

            } else {

                Subscription::create([
                    'type'         => $request->type,
                    'magazine_id'  => $request->magazine_id,
                    'subscriber_id' => auth()->user()->id
                ]);

                return $this->response(message: 'You Have Successfully Subscribed');

            }
            

        } else {

            return $this->response(message: 'Sorry You Dont Subscriber');

        }

    }

}
