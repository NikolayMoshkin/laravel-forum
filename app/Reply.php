<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];  //используем вместо метода boot. Подгружаем зависимости в одном sql запросе


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
