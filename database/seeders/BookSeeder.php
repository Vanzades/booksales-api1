<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // ambil author by name → supaya relasi jelas
        $jk    = Author::where('name','J.K. Rowling')->first();
        $grrm  = Author::where('name','George R.R. Martin')->first();
        $ynh   = Author::where('name','Yuval Noah Harari')->first();
        $asimov= Author::where('name','Isaac Asimov')->first();
        $hm    = Author::where('name','Haruki Murakami')->first();

        $books = [
            ['author_id' => $jk?->id,    'title' => 'Harry Potter and the Sorcerer\'s Stone', 'isbn' => '9780747532699', 'stock'=>50, 'price'=>120000, 'published_year'=>1997],
            ['author_id' => $grrm?->id,  'title' => 'A Game of Thrones',                      'isbn' => '9780553103540', 'stock'=>40, 'price'=>150000, 'published_year'=>1996],
            ['author_id' => $ynh?->id,   'title' => 'Sapiens',                                'isbn' => '9780099590088', 'stock'=>35, 'price'=>135000, 'published_year'=>2011],
            ['author_id' => $asimov?->id,'title' => 'Foundation',                             'isbn' => '9780553293357', 'stock'=>30, 'price'=>110000, 'published_year'=>1951],
            ['author_id' => $hm?->id,    'title' => 'Kafka on the Shore',                     'isbn' => '9781400079278', 'stock'=>25, 'price'=>125000, 'published_year'=>2002],
        ];

        foreach ($books as $b) {
            // Pastikan author_id ada
            if ($b['author_id']) {
                Book::create($b);
            }
        }
    }
}
