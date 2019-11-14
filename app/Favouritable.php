<?php


namespace App;


trait Favouritable
{
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if(!$this->favourites()->where($attributes)->exists()) {
            $this->favourites()->create($attributes);//используем morphMany зависимость
        }
        else
            $this->favourites()->where($attributes)->delete();
        return $this->favourites()->count();
    }

    public function isFavourited()
    {
        return $this->favourites->where('user_id', auth()->id())->count();
    }
}
