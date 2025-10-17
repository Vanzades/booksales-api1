<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'J.K. Rowling',        'email' => 'jk@books.com'],
            ['name' => 'George R.R. Martin',  'email' => 'grrm@books.com'],
            ['name' => 'Yuval Noah Harari',   'email' => 'ynh@books.com'],
            ['name' => 'Isaac Asimov',        'email' => 'asimov@books.com'],
            ['name' => 'Haruki Murakami',     'email' => 'murakami@books.com'],
        ];

        foreach ($authors as $a) {
            Author::create($a);
        }
    }
}
