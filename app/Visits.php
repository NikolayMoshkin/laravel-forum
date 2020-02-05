<?php


namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ? : 0;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    protected function cacheKey()
    {
        $model_name = strtolower((new \ReflectionClass($this->model))->getShortName() . 's');

        return "$model_name.{$this->model->id}.visits";
    }
}
