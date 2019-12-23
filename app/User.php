<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function foo\func;

class User extends Authenticatable
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('threadsCount', function ($builder){
           $builder->withCount('threads');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class, 'user_id')->latest();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id')->latest();
    }

    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);

    }

    public function read(Thread $thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now() //добавляем в кэш дату посещения страницы поста для обновления статуса просмотренных постов
        );
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
