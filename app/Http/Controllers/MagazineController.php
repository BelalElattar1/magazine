<?php

namespace App\Http\Controllers;

use App\ResponseTrait;
use App\Models\Magazine;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\MagazineResource;
use Illuminate\Support\Facades\Validator;

class MagazineController extends Controller
{
    use ResponseTrait;

    public function index() {

        try {

            JWTAuth::parseToken()->authenticate();

            if(auth()->user()->role == 'publisher') {

                $magazines = Magazine::where('publisher_id', auth()->user()->id)->get();
                if($magazines) {
                
                    return $this->response(message: 'Show All Magazines Suc', data: MagazineResource::collection($magazines));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            } elseif(auth()->user()->role == 'admin') {

                $magazines = Magazine::with('publisher')->get();
                if($magazines) {
                
                    return $this->response(message: 'Show All Magazines Suc', data: MagazineResource::collection($magazines));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            } elseif(auth()->user()->role == 'subscriber') {

                $magazines = Magazine::whereHas('subscriptions', function ($query) {
                    $query->where('status', '!=', 'expire');
                    $query->where('subscriber_id', auth()->user()->id);
                })->with('publisher')->get();

                if($magazines) {
                
                    return $this->response(message: 'Show All Magazines Suc', data: MagazineResource::collection($magazines));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            }
 
        }  catch (JWTException $e) {

            $magazines = Magazine::with('publisher')->get();
            if($magazines) {
            
                return $this->response(message: 'Show All Magazines Suc', data: MagazineResource::collection($magazines));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        }

    }

    public function store(Request $request) {

        if(auth()->user()->role == 'publisher') {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:3', 'max:70'],
                'description' => ['required', 'string', 'min:15', 'max:255']
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            Magazine::create([
                'name' => $request->name,
                'description' => $request->description,
                'publisher_id' => auth()->user()->id
            ]);

            return $this->response(message: 'Created Suc');

        } else {

            return $this->response(message: 'Sorry You Dont Publisher');

        }

    }

}
