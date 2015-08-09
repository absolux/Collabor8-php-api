<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Project::observe(new \App\Observers\ActivityObserver);
        
        \App\Task::observe(new \App\Observers\ActivityObserver());
        
        \App\Activity::creating(function($activity) {
            $activity->user()->associate(auth()->user());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
