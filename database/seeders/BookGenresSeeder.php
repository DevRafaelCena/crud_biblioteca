<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookGenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $genres = [
            'Ficção',
            'Ficção Científica',
            'Mistério',
            'Romance',
            'Fantasia',
            'Suspense',
            'Terror',
            'Biografia',
            'História',
            'Autoajuda',
        ];

        foreach ($genres as $genre) {
            \App\Models\BookGenres::create(['name' => $genre]);
        }
    }
}
