<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id', 'book_copy_id', 'loan_date', 'return_due_date', 'returned_date', 'late_fee'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }
}
