<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function getRouteKeyName() //TODO:для web файла. Использовать в качестве идентификатора не id, а slug
    {
        return 'slug';
    }
    public function threads()
    {
        return $this->hasMany(Thread::class, 'channel_id');
    }
}
