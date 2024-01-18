<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // eliminar carpetas de imagenes
        Storage::deleteDirectory('articles');
        Storage::deleteDirectory('categories');

        // crear carpetas donde se almacenaran las imagenes
        Storage::makeDirectory('articles');
        Storage::makeDirectory('categories');
        // llamar al seeder
       $this->call(UserSeeder::class);

       // factories
       Category::factory(8)->create();
       Article::factory(20)->create();
       Comment::factory(20)->create();
    }
}
