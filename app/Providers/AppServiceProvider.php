<?php

namespace App\Providers;

use App\Student;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

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
        $students=Student::select('id','name','group_name')->get();
        View::share('students',$students);
    }
}
