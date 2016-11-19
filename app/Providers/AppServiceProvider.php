<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Validator::extend('datetime_greater_than_field', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return strtotime($value) > strtotime($min_value);
        });

        Validator::replacer('datetime_greater_than_field', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', trans("validation.attributes.".$parameters[0]), $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
