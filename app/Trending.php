<?php


namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * @param $amount
     * @return array
     */

    public function get($amount = 4)
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, $amount-1));
    }

    public function push($thread)
    {
        //по стандартной схеме в таблицу trending_threads мы бы поместили ячейку с именем статьи (или id), но потом пришлось бы делать sql запрос, чтобы достать все поля статьи
        Redis::zincrby($this->cacheKey(), 1, json_encode([ //TODO:Redis - помещаем в таблицу trending_threads ячейку с именем json_encode(объект) и увеличиваем значение, которое ассоциируется с этой ячейкой, на 1
            'id' => $thread->id,
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function clear()
    {
        Redis::del($this->cacheKey());
    }

    protected function cacheKey() //TODO:Redis - способ хранения разных ключей для обычного режима и для тестов
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }
}
