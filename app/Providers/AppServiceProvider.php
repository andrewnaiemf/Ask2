<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('exists_with_keys', function ($attribute, $value, $parameters, $validator) {
            $table = $parameters[0];
            $ids = array_keys(data_get($validator->getData(), $attribute));
            return \DB::table($table)->whereIn('id', $ids)->count() === count($ids);
        });
    }
}
