<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use Astrotomic\Translatable\Locales;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\ScheduleService;

class AuthController extends Controller
{

    public function register(Request $request)
    {


        $validator = $this->validateRegistrationRequest($request);

        if ($validator->fails()) {
            return $this->returnValidationError(401, $validator->errors()->all());
        }

        $user = $this->createUser($request);
        $user->load(['provider.department','provider.subdepartment','provider.images','provider.schedule']);
        $user['provider']['clinics'] = Clinic::all();


        $credentials = $request->only(['phone','password']);

        $token= JWTAuth::attempt($credentials);
        if (!$token) {
            return $this->unauthorized();
        }

      return  $this->respondWithToken($token,$user);
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        $remember = $request->boolean('remember_me', false);

        $credentials = request(['phone', 'password']);


        if (! $token = JWTAuth::attempt($credentials,$remember)) {
            return $this->unauthorized();
        }


        $user = User::find(auth()->user()->id);

        $user_status = $user->provider->status;
        if($user_status  != 'Accepted') {
            return $this->returnError(__('api.pleaseContactWithAdministrator'));
        }

        $this->device_token($request->device_token, $user);

        $user->load(['provider.department',
                        'provider.subdepartment',
                        'provider.images',
                        'provider.ratings',
                    ]);
        $user['provider']['clinics'] = Clinic::all();

        $scheduleService = new ScheduleService();
        $workTime = $scheduleService->getProviderWorkTime($user->provider->id);

        $user['schedule'] =  $workTime ;
        return $this->respondWithToken($token ,$user);
    }



    public function reset(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'password' => 'required|confirmed|string|min:6',
            'password_confirmation' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        $user = User::where('phone',$request->phone)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->returnSuccessMessage( trans("api.Password_updated_successfully") );
    }



    public function logout()
    {
        auth()->logout();
        return $this->returnSuccessMessage( trans("api.logged_out_successfully") );
    }


    protected function createUser(Request $request)
    {
        $user = User::create([
            'uuid' => strtotime("now"),
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city_id' => $request->city_id
        ]);

        $this->device_token($request->device_token, $user);

        $user->update(['account_type' => 'Provider','profile' => '']);
        if($request->file('profile')){
            $this->userProfile( $request->file('profile'), $user);
        }

        $this->attachProviderData($request, $user);

        return $user;
    }



    public function refresh()
    {
        $user=User::find(auth()->user()->id);
        return $this->respondWithToken( $user->refresh(), $user);
    }


    protected function respondWithToken($token, $user)
    {
        return $this->returnData(['user' => $user , 'access_token' => $token]);
    }


    private function device_token($device_token,  $user){

        if(!isset($user->device_token)){
            $user->update(['device_token'=>json_encode($device_token)]);
        }else{
            $devices_token = $user->device_token;

            if(! in_array( $device_token , $devices_token) ){
                array_push($devices_token ,$device_token );
                $user->update(['device_token'=>json_encode( $devices_token)]);
            }
        }
    }

    protected function validateRegistrationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|confirmed|string|min:6',
            'password_confirmation' => 'required',
            'department_id' => 'integer|required|exists:departments,id',
            'subdepartment_id' => 'integer|required|exists:departments,id',
            'location' => 'required|string',
            'city_id' => 'integer|required|exists:cities,id',
            'commercial_register' => 'required|string',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'terms' => 'accepted',
            'device_token' => 'required|string'
        ]);
    }

    private function userProfile($profile, $user){

        $path = 'Provider/' .$user->id. '/';

        $imageName = $profile->hashName();
        $profile->storeAs('public/'.$path,$imageName);
        $full_path = $path.$imageName;
        $user->update(['profile'=> $full_path]);
    }

    private function attachProviderData($request, $user){
        $providerData = [
            'user_id' => $user->id,
            'commercial_register' => $request->commercial_register,
            'location' => $request->location,
            'department_id' => $request->department_id,
            'subdepartment_id' => $request->subdepartment_id,
        ];

        $provider = Provider::create($providerData);

        return $provider;
    }

}
