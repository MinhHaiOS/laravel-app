<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(1000)->create();
        Author::factory(30)->create();
        $bookIds = Book::all()->pluck('id')->toArray();
        foreach($bookIds as $bookId)
        {
            $range = range(1, rand(1, 2));
            foreach($range as $index)
            {
                DB::table('book_authors')->insert([
                    'book_id' => $bookId,
                    'author_id' => rand(1, 30),
                ]);
            }

        }
    }

}
