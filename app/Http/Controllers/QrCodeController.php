<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookQrCode;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeController extends Controller
{
    /**
     * Generate a QR code for a book.
     */
    public function generateQrCode($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Concatenate book details

        $bookDetails = json_encode([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'description' => $book->description,
            'image' => $book->image,
            'published_year' => $book->published_year,
        ]);

        

        // Generate QR code
        $qrCode = new QrCode($bookDetails);
        $writer = new PngWriter();
        $qrCodeData = $writer->write($qrCode)->getString();

        // Save QR code image

        $qrCodePath = storage_path('app/public/qr_codes/' . $book->id .  '_' . now()->format('Ymd_His') . '.png');




        file_put_contents($qrCodePath, $qrCodeData);

        // Save QR code information in the database
        BookQrCode::updateOrCreate(
            ['book_id' => $book->id],

            ['qr_code_image' => 'qr_codes/' . $book->id .  '_' . now()->format('Ymd_His') . '.png'],

            ['qr_code_image' => 'qr_codes/' . $book->id . '.png'],


            ['qr_code_image' => 'qr_codes/' . $book->id . '.png']
        );

        return redirect()->route('qr-codes.index')->with('success', 'QR code generated successfully');
    }

    /**
     * Display a listing of the QR codes.
     */
    public function index()
    {
        $books = Book::with('qrCode')->get();
        return view('qr-codes.index', compact('books'));
    }
}
