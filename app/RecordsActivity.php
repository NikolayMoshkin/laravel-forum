<?php


namespace App;


trait RecordsActivity
{
    protected static function bootRecordsActivity()  //в трейт можно добавить статитческий метод bootTraitName и тогда этот метод добавиться в boot модели
    {
        if(auth()->guest()) return;

        foreach(static::getActivitiesToRecord() as $event)
        {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord()
    {
        return ['created', 'deleted'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    protected function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        return $event.'_' . strtolower((new \ReflectionClass($this))->getShortName()); //получаем строчку (event_shortModelName), например: 'created_thread'
    }

}
