<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator=Validator::make($request->all(), [
            'rate' => 'required|numeric|between:0,5',
            'feedback' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }


        $rate = Rating::create([
            'user_id' =>  $request->user_id,
            'rate' => $request->rate,
            'feedback' => $request->feedback,
            'rated_user_id' => auth()->user()->id
        ]);

        $this->createNotification( $rate );

        return $this->returnSuccessMessage( trans("api.ratingSetSuccessfully") );


        // return $this->returnError( trans("api.InvalidRequest"));
    }

    public function createNotification( $rate ){

        $provider = User::find($rate->user_id);

        Notification::create([
            'user_id' =>  $rate->user_id,
            'notified_user_id' => $rate->rated_user_id,
            'type' => 'rating',
            'screen' => 'rating',
            'data' =>$rate
        ]);

        PushNotification::send([$provider->device_token], 'rating', $rate);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
