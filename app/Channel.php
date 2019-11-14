<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function getRouteKeyName() //для web файла. Использовать в качестве иденификатора на id, а slug
    {
        return 'slug';
    }
    public function threads()
    {
        return $this->hasMany(Thread::class, 'channel_id');
    }
}
