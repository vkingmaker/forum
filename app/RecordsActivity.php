<?php
namespace App;

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;

        foreach (static::getRecordEventsToRecord() as $event) {

            static::$event(function($model) use ($event) {

                $model->recordActivity($event);

            });
        }

        static::deleting(function($model){
            $model->activity()->delete();
        });
    }


    protected static function getRecordEventsToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),

            'type' => $this->getActivityType($event)
        ]);

    }


    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }


    public function getActivityType($event)
    {
        return $event.'_'. strtolower((new \ReflectionClass($this))->getShortName());
    }
}
