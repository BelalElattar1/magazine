<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\ResponseTrait;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use ResponseTrait;

    public function store(Request $request) {

        if(auth()->user()->role == 'admin') {

            $validator = Validator::make($request->all(), [
                'price' => ['required', 'integer'],
                'payment_method' => ['required', 'string', 'min:3', 'max:255'],
                'subscription_id' => ['required', 'integer', 'exists:subscriptions,id']
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $subscription = Subscription::where('id', $request->subscription_id)->where('status', 'waiting')->first();
            if($subscription) {

                DB::transaction(function () use($subscription, $request) {

                    Payment::create([
                        'price'           => $request->price,
                        'payment_method'  => $request->payment_method,
                        'subscription_id' => $subscription->id,
                        'subscriber_id'   => $subscription->subscriber_id
                    ]);

                    $subscription->update([
                        'status' => 'active',
                        'start'  => Carbon::now()->format("Y-m-d"),
                        'end'    => $subscription->type == 'monthly' ? Carbon::now()->addMonth()->format("Y-m-d") : Carbon::now()->addYear()->format("Y-m-d")
                    ]);

                });

                return $this->response(message: 'Created Suc');

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } else {

            return $this->response(message: 'Sorry You Dont Admin');

        }

    }

}
