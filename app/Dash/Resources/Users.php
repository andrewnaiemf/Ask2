<?php

namespace App\Dash\Resources;

use Dash\Resource;
use Illuminate\Validation\Rule;

class Users extends Resource
{
    public static $model         = \App\Models\User::class ;
    public static $group         = 'users';
    public static $displayInMenu = true;
    // public static $icon          = '<i class="fa fa-users"></i>';
    public static $title         = 'name';
    public static $search        = [
        'id',
        'name',
        'email',
    ];
    public static $searchWithRelation = [];

    public static function customName()
    {
        return __('dash.user.users');
    }

    public function query($model)
    {
        return $model->where('account_type', 'user');
    }

    public static function vertex()
    {
        return [

        ];
    }

    public function fields()
    {
        return [
            id()->make(__('dash::dash.id'), 'id'),
            text() ->make(__('dash.user.name'), 'name')
                   ->ruleWhenCreate('string', 'min:4')
                   ->ruleWhenUpdate('string', 'min:4')
                   ->columnWhenCreate(6)
                   ->showInShow(),
            text()->make(__('dash.user.phone'), 'phone') ,
            text()->make(__('dash.user.rating'), 'rating')->hideInUpdate(),
            hasMany()->make(__('dash.user.addresses'),'addresses', Addresses::class),
            checkbox()->make(__('dash.user.can_share_news'), 'can_share_news')->trueVal(1)->falseVal(0)
        ];
    }

    public function actions()
    {
        return [

        ];
    }

    public function filters()
    {
        return [
        ];
    }

}
