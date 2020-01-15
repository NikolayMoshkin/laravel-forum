<?php

namespace App\Providers;

use App\Channel;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        DB::connection()->enableQueryLog(); //TODO: включаем логирование запросов, затем в виде можно использовать: dd(Illuminate\Support\Facades\DB::getQueryLog())

        date_default_timezone_set('Europe/Moscow');
        Carbon::setLocale('ru');  //TODO: изменить язык пакета Carbon

        View::composer('*', function ($view) {    //передаем в любой вид ('*') переменную $channels
            $channels = Cache::rememberForever('channels', function () { //TODO: кэшируем sql запрос
                return \App\Channel::all();
            });

            $view->with('channels', $channels);
        });
//        View::share('channels', Channel::all());  //аналог строчек выше

        Validator::extend('spamfree', '\App\Rules\SpamFree@passes');  //связываем название пользователского фильтра и модель

    }
}
