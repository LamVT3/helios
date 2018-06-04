<?php

namespace App\Providers;

use App\Notification;
use Illuminate\Support\Facades\View;
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
	    $notification = Notification::orderBy('_id','DESC')->first();

	    View::composer('components.notify', function ($view) use ( $notification ) {
		    $view->with('notification', $notification);
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
