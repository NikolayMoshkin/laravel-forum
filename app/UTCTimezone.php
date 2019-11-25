<?php


namespace App;


use Carbon\Carbon;

trait UTCTimezone
{
    protected static $city = 'Moscow';
    protected static $url = 'http://worldtimeapi.org/api/timezone/Europe/';

    protected static function bootUTCTimeZone()  //в трейт можно добавить статитческий метод bootTraitName и тогда этот метод добавиться в boot модели
    {
        static::creating(function ($model) {
            $model->created_at = static::getCurrentTime();
        });
    }


    protected static function getCurrentTime($city = null) {
        if ($city){
            $response = static::sendRequest($city);
        }
        else $response =  static::sendRequest(static::$city);

        return static::getCarbonTime($response);
    }

    protected static function sendRequest($city)
    {
        $ch = curl_init(static::$url.$city);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    protected static function getCarbonTime($response)
    {
        $datetime = new Carbon(date('Y-m-d H:i:s', json_decode($response, true)['unixtime']));
        return $datetime;
    }



}
