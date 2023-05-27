<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenres extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;

    public function books()
    {
        return $this->hasMany(Book::class, 'genre_id');
    }
}
