<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Models\DocumentProvider;
use App\Models\Provider;
use App\Models\Schedule;
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

        $providerId = $user->provider->id; // Assign the provider ID to $providerId

        // Check if the authenticated user is the same as the provider
        if ($user->provider->id !== $providerId) {
            return $this->returnError(__('api.unauthorized'));
        }


        $user->load(['provider.department',
                        'provider.subdepartment',
                        'provider.images',
                        'provider.ratings',
                        'provider.clinics.schedules.clinicScheduleDoctors'

                    ]);

        $providerData = $user->toArray();
        $providerData['provider']['clinics'] = [];

        foreach ($user->provider->clinics as $clinic) {
            $clinicData = $clinic->toArray();
            $clinicData['schedules'] = [];

            foreach ($clinic->schedules as $schedule) {
                // Check if the schedule belongs to the provider
                if ($schedule->provider_id === $providerId) {
                    $scheduleData = $schedule->toArray();
                    $scheduleData['doctors'] = $schedule->clinicScheduleDoctors;
                    unset($scheduleData['clinic_schedule_doctors']);

                    $clinicData['schedules'][] = $scheduleData;
                }
            }

            $providerData['provider']['clinics'][] = $clinicData;
        }

        $scheduleService = new ScheduleService();
        $workTime = $scheduleService->getProviderWorkTime($user->provider->id);

        $providerData['schedule'] =  $workTime ;
        return $this->returnData(['user' => $providerData]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        $userId = auth()->user()->id;
        $validation =  $this->validateUserData( $request );

        if ( $validation) {
            return $validation;
        }

        $providerData = $request->except(['city_id', 'email', 'profile', 'image']);

        $this->workDescription($userId, $request['email'] , $request['city_id']);

        $this->providerDocuments($userId, $request['profile'] , $request['image']);

        if ($request['schedule']) {
            $this->providerSchedule($userId, $request['schedule'] ,$request['open_all_time']);
        }


        if (!empty($providerData)) {
            $provider = Provider::where('user_id',$userId)->first();
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

    public function providerSchedule($userId, $schedules, $open_all_time){
        $provider = Provider::where('user_id',$userId)->first();


        $provider->update(['open_all_time' => $open_all_time]);
        foreach ($schedules as $id => $schedule) {
            $day = $provider->schedule()->where('day_of_week', $schedule['day_of_week'])->first();
            if($day){
                $day->update($schedule);
            }else{
                $schedule['provider_id'] = $provider->id;
                Schedule::create( $schedule);
            }
        }

    }

    public function updateProfilePicture($user, $path, $profile){

        $userProfile =  $user->profile;
        if ($userProfile) {

            $segments = explode('/', $userProfile);
            $imageName = $segments[2];
            $profile->storeAs('public/'.$path,$imageName);

        }else{

            $imageName = $profile->hashName();
            $profile->storeAs('public/'.$path,$imageName);
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
            $image->storeAs('public/'.$path,$imageName);
            $full_path = $path.$imageName;

            DocumentProvider::create([
                'provider_id' => $user->provider->id,
                'name' => 'describe_image',
                'path' => $full_path,
            ]);

        }
    }


    public function validateUserData ( $request ) {

        $validator = Validator::make($request->all(), [

            'info' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'latitude' => ['nullable','string', 'max:255', 'regex:/^[-]?((([0-8]?[0-9])(\.(\d+))?)|(90(\.0+)?))$/'],
            'longitude' => ['nullable','string', 'max:255', 'regex:/^[-]?((([0-9]?[0-9]?[0-9])(\.(\d+))?)|(1[0-7][0-9](\.\d+)?)|(180(\.0+)?))$/'],
            'facebook_link' => ['nullable','string', 'max:255', 'url'],
            'instagram_link' => ['nullable','string', 'max:255', 'url'],
            'twitter_link' => ['nullable','string', 'max:255', 'url'],
            'snapchat_link' => ['nullable','string', 'max:255', 'url'],
            'linkedin_link' => ['nullable','string', 'max:255', 'url'],
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean'
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
