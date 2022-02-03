<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::all()->each(function (User $user){
            $user->notes()->saveMany(Note::factory( 2)->make());
        });
        Note::all()->each(function (Note $note) {
            $note->note_users()->attach(User::where('id', '<>', $note->user_id)->inRandomOrder()->take(2)->get());
        });
    }
}
