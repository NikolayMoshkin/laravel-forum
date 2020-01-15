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
        factory(\App\User::class)->create([
            'name' =>'admin',
            'email' => '1@m.ru',
            'password' => bcrypt('12345678')
        ]);

       $threads = factory(App\Thread::class, 20)->create();
       $threads->each(function($thread){
           factory(\App\Reply::class, 5)->create(['thread_id'=>$thread->id]);
       });
    }
}
