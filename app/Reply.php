<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable, RecordsActivity;

    protected $guarded = [];
    protected $touches = ['thread']; //обновляет updated_at поле родительской модели через вызов метода и belongsTo

    protected $with = ['owner', 'thread', 'favourites'];  //используем вместо метода boot. Подгружаем зависимости в одном sql запрос

    // полe $appends магическим образом позволяет вызвать методы модели с именем get_customMethodName_attribute (например getIsFavouritedAttribute)
    // и добавить результат выполнения метода в конце json ответа
//    protected $appends = ['isFavourited'];


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path().'#reply-'.$this->id;
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }
}
