<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(){

        $departments = Department::with('subdepartments')->whereNull('parent_id')->get();

        return $this->returnData( $departments );
    }
}
