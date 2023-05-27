<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    use HasFactory;
    protected $fillable = ['book_id', 'user_id', 'return_due_date', 'date_returned'];

    public function book()
    {
        return $this->belongsTo(Books::class);
    }

    public function user()
    {
        return $this->belongsTo(UsersLibrary::class);
    }
}
