<?php

namespace App\Providers;

use App\Dash\Dashboard\Help;
use App\Dash\Resources\AdminGroupRoles;
use App\Dash\Resources\AdminGroups;
use App\Dash\Resources\Admins;
use App\Dash\Resources\Beds;
use App\Dash\Resources\Categories;
use App\Dash\Resources\Cities;
use App\Dash\Resources\Clinics;
use App\Dash\Resources\Departments;
use App\Dash\Resources\HotelServices;
use App\Dash\Resources\MainDepartments;
use App\Dash\Resources\Questions;
use App\Dash\Resources\Rooms;
use App\Dash\Resources\RoomTypes;
use App\Dash\Resources\SubDepartments;
use App\Dash\Resources\Users;
use Dash\DashServiceProviderInit;

class DashServiceProvider extends DashServiceProviderInit
{
    /**
     * put your dashboard to rendering in home page
     * @return array
     */
    public static function dashboards()
    {
        return [
            Help::class,
        ];
    }

    /**
     * Put Your Resources Here to register in Dashboard
     * @return array
     */
    public function resources()
    {
        return [
            Users::class,
            Beds::class,
            Cities::class,
            HotelServices::class,
            Questions::class,
            Rooms::class,
            RoomTypes::class,
            Categories::class,
            MainDepartments::class,
            SubDepartments::class,
            Clinics::class,
            // Admins::class,
            // AdminGroups::class,
            // AdminGroupRoles::class,
        ];
    }

    /**
     * put notification class
     * @return array
     */
    public static function notifications()
    {
        return [
        ];
    }

    /**
     * Custom Blank Pages
     * @return array
     */
    public static function blankPages()
    {
        return [
        ];
    }

    /**
     * boot method
     * please dont make any change here
     */
    public function boot()
    {
        parent::boot();
    }
}
