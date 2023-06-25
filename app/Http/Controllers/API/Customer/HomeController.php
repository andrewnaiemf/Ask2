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

        $user = User::with('city')->find(auth()->user()->id);

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

    public function search($searchTerm, Request $request){

        $perPage = $request->header('per_page', 10);

        if ($request->search_for == 'provider') {

            $data = User::where('account_type', 'provider')
            ->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->simplePaginate($perPage);
        }else{

            $data = Department::where('name_'.app()->getLocale(), 'LIKE', '%' . $searchTerm . '%')
            ->simplePaginate($perPage);
        }
        // $services = Provider::where('service', 'LIKE', '%' . $searchTerm . '%')
        //     ->where('status', 'Accepted')
        //     ->paginate(10);

        return $this->returnData(['result' => $data]);

    }
}
