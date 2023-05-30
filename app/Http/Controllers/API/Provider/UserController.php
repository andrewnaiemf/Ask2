<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\DocumentProvider;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\ScheduleService;

class UserController extends Controller
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
        //
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

    public function me()
    {
        $user = User::find(auth()->user()->id);

        $user_status = $user->provider->status;
        if($user_status  != 'Accepted') {
            return $this->returnError(__('api.pleaseContactWithAdministrator'));
        }

        $user->load(['provider.department','provider.subdepartment','provider.images']);
        $scheduleService = new ScheduleService();
        $workTime = $scheduleService->getProviderWorkTime($user->id);

        $user['schedule'] =  $workTime ;

        return $this->returnData(['user' => $user ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userId = auth()->user()->id;
        $validation =  $this->validateUserData( $request );

        if ( $validation) {
            return $validation;
        }

        $providerData = $request->except(['city_id', 'email', 'profile', 'image']);

        $this->workDescription($userId, $request['email'] , $request['city_id']);

        $this->providerDocuments($userId, $request['profile'] , $request['image']);

        $this->providerSchedule($userId, $request['schedule'] ,$request['is_open_all_time']);


        if (!empty($providerData)) {
            $provider = Provider::find($userId);
            $provider->update($providerData);
        }

        return $this->returnSuccessMessage( trans("api.user'sdataUpdatedSuccessfully") );
    }

    public function workDescription($userId, $email, $city_id){

        $userData = [];
        if ($email) {
            $userData['email'] = $email;
        }

        if ($city_id) {
            $userData['city_id'] = $city_id;
        }

        if (!empty($userData)) {
            $user = User::find($userId);
            $user->update($userData);
        }

    }

    public function providerDocuments($userId, $profile, $image){

        $user = User::find($userId);
        $path = 'Provider/' .$userId. '/';

        if ($profile) {
           $this->updateProfilePicture($user, $path, $profile);
        }

        if ($image) {
            $this->updateProviderPlaceImages($user,$image);
        }

    }

    public function providerSchedule($userId, $schedule, $open_all_time){

        $user = Provider::find($userId);

        if($open_all_time && empty($schedule)){//provider open all day
            $user->update(['open_all_time' => $open_all_time]);
        }else{
            $user->update(['open_all_time' => 0]);// provider open in particular time

            $user->schedule()->forceDelete(); // Delete existing schedules
            $user->schedule()->createMany($schedule); // Create new schedules
        }

    }

    public function updateProfilePicture($user, $path, $profile){

        $userProfile =  $user->profile;
        if ($userProfile) {

            $segments = explode('/', $userProfile);
            $imageName = $segments[2];
            $profile->storeAs($path,$imageName);

        }else{

            $imageName = $profile->hashName();
            $profile->storeAs($path,$imageName);
            $full_path = $path.$imageName;

            $user->update([
                'profile' => $full_path
            ]);
        }
    }

    public function updateProviderPlaceImages($user, $images){

        $path = 'Provider/' .$user->id. '/placeImages/';

       foreach ($images as $image) {

            $imageName = $image->hashName();
            $image->storeAs($path,$imageName);
            $full_path = $path.$imageName;

            DocumentProvider::create([
                'provider_id' => $user->id,
                'name' => 'describe_image',
                'path' => $full_path,
            ]);

        }
    }


    public function validateUserData ( $request ) {

        $validator = Validator::make($request->all(), [

            'info' => 'string|max:255',
            'service' => 'string|max:255',
            'city_id' => 'exists:cities,id',
            'email' => [
                'email',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'latitude' => ['string', 'max:255', 'regex:/^[-]?((([0-8]?[0-9])(\.(\d+))?)|(90(\.0+)?))$/'],
            'longitude' => ['string', 'max:255', 'regex:/^[-]?((([0-9]?[0-9]?[0-9])(\.(\d+))?)|(1[0-7][0-9](\.\d+)?)|(180(\.0+)?))$/'],
            'facebook_link' => ['string', 'max:255', 'url'],
            'instagram_link' => ['string', 'max:255', 'url'],
            'twitter_link' => ['string', 'max:255', 'url'],
            'snapchat_link' => ['string', 'max:255', 'url'],
            'linkedin_link' => ['string', 'max:255', 'url'],
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
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
