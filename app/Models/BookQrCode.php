<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookQrCode extends Model
{
    use HasFactory;

    protected $table = 'books_qr_code'; // Specify the correct table name

    protected $fillable = ['book_id', 'qr_code_image'];
}
