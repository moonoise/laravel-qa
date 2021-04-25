<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();
        \DB::table('users')->delete();

        factory(App\User::class,10)->create()->each(function ($u) {
            $u->Questions()
                ->saveMany(
                    factory(App\Question::class,rand(1,5))->make()
                )
                ->each(function ($q) {
                    $q->answers()->saveMany(factory(App\Answer::class,rand(1,5))->make());
                });
        });
    }
}
