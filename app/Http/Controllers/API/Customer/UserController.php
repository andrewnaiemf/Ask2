<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\DocumentProvider;
use App\Models\Provider;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\ScheduleService;
use Storage;

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

    public function me(){

        $user = User::with('city')->find(auth()->user()->id);

        return $this->returnData(['user' => $user]);

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

        $customerData = $request->except([ 'profile']);

        $this->updateProfilePicture(auth()->user(), $request['profile']);

        if (!empty($customerData)) {
            $customer = User::find($userId);
            $customer->update($customerData);
        }

        return $this->returnSuccessMessage( trans("api.user'sdataUpdatedSuccessfully") );
    }

    public function updateProfilePicture($user, $profile){

        $path = 'Customer/' .$user->id. '/';
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


            'phone' => ['nullable',
                    'numeric',
                    Rule::unique('users')->ignore(auth()->user()->id),
                ],
            'city_id' => 'nullable|exists:cities,id',
            'name' => 'nullable|string',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

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
