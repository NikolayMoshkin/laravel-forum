<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'channel'];  //аналог метода addGlobalScope

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

//        static::addGlobalScope('owner', function ($builder) {  //при использовании далее метода withoutGlobalScopes  - не будут загружаться GlobalScopes
//            $builder->with('owner');
//        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->withCount('favourites');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function path()
    {
        return "/threads/" . $this->channel->slug . "/" . $this->id;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);

    }
}
