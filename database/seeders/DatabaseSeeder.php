<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Eniko Polyak',
            'email' => 'polyakeniko23@gmail.com',
            'password' => bcrypt('eniko123'),
            'email_verified_at' => now(),
            'role' => 'user',
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'Fabian Csernak',
            'email' => 'csernakfabian@gmail.com',
            'password' => bcrypt('fabi123'),
            'email_verified_at' => now(),
            'role' => 'user',
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(10)->create();

        Category::factory()->count(5)->create();

        Book::factory()->count(10)->create();

        BookCopy::factory()->count(50)->create();

        Reservation::factory()->count(10)->create();

        Loan::factory()->count(10)->create();

        Review::factory()->count(10)->create();
    }
}
