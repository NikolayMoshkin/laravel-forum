<?php


namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ? : 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());
    }

    protected function visitsCacheKey()
    {
        $model_name = strtolower((new \ReflectionClass($this))->getShortName() . 's');

        return "$model_name.{$this->id}.visits";
    }
}
