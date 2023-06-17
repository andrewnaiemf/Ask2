<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index(){

        $user = User::find(auth()->user()->id);

        $mostRate = $this->mostRate();
        $mainDepartments = $this->mainDepartments();

        return $this->returnData(['user' => $user, 'mostRate' => $mostRate, 'mainDepartments' => $mainDepartments]);

    }

    function mostRate() {

        $providers = Provider::all()->filter(function ($provider) {
                        return $provider->rating > 3;
                    })->sortByDesc('rating')->values();
        return  $providers;
    }

    function mainDepartments(){

        $departments = Department::with('subdepartments')->whereNull('parent_id')->get();
        return  $departments;

    }
}
