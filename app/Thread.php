<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
//    use UTCTimezone;

    protected $guarded = [];
    protected $appends = ['isSubscribedTo']; //всегда добавляет в коллекцию указанные поля

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
            $thread->replies->each->delete();
        });
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->withCount('favourites'); //сразу подгружаем счетчик favourites
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply(array $reply)
    {
       $reply =  $this->replies()->create($reply);

        event(new ThreadHasNewReply($reply));

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function path()
    {
        return "/threads/" . $this->channel->slug . "/" . $this->id;
    }

    public function scopeFilter($query, $filters)  //добавив "scope" к названию метода, можно работать с builder'ом ($query)
    {
        return $filters->apply($query);

    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()
            ->create([
           'user_id' => $userId ? $userId : auth()->id(),

        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId?:auth()->id())
            ->delete();
        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()  //getFooAttribute - мутатор, позволяет обращаться к методу , как к полю (object->foo)
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)  //этот метод используется во view файле (threads/index)
    {
        $user = $user ?: auth()->user();

        return $user && $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }
}
