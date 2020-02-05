<?php


namespace App;


trait Favouritable
{

    protected static function bootFavouritable()  //в трейт можно добавить статитческий метод bootTraitName и тогда этот метод добавиться в boot вызвашей трейт модели
    {
        if(auth()->guest()) return;
        static::deleting(function($model){
            $model->favourites->each->delete();
        });
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favouriteToggle()
    {
        $attributes = ['user_id' => auth()->id()];
        if(!$this->favourites()->where($attributes)->exists()) {
            $this->favourites()->create($attributes);//используем morphMany зависимость
        }
        else {
            $this->favourites()->where($attributes)->first()->delete();
        }
        return $this->favourites()->count();
    }

    public function isFavourited()
    {
        return $this->favourites->where('user_id', auth()->id())->count();
    }
}
