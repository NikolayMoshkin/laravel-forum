<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $threats = factory(App\Thread::class, 50)->create();
       $threats->each(function($threat){
           factory(\App\Reply::class, 10)->create(['threat_id'=>$threat->id]);
       });
    }
}
