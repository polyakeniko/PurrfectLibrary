<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookForSale extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_price',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
