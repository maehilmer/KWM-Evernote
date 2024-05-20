<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $note = new \App\Models\Note;
        $note->title = "Einkaufen gehen";
        $note->description = "für Filmabend mit Freunden";
        $note->user_id = 1;
        $note->listoverview_id = 1;
        $note->save();



        //get the first user
        $user = \App\Models\User::all()->first();
        $note->user()->associate($user);
        $note->save();

        // add images to note
        $image1 = new \App\Models\Image;
        $image1->title = "Cover 1";
        $image1->url = "https://picsum.photos/200";

        $image2 = new \App\Models\Image;
        $image2->title = "Cover 2";
        $image2->url = "https://picsum.photos/200";
        $note->images()->saveMany([$image1,$image2]);
        $note->save();
    }
}
