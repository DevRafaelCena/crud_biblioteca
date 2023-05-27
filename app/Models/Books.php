<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'genre_id'];

    public function genres()
    {
        return $this->belongsTo(BookGenres::class, 'genre_id');
    }
}
