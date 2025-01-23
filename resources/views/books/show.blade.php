<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default.jpg') }}" alt="{{ $book->title }}" class="mt-4 w-32 h-32 object-cover rounded-md">
                    <h3 class="text-2xl font-semibold">Title: {{ $book->title }}</h3>
                    <p><strong>Author:</strong> {{ $book->author }}</p>
                    <p><strong>Category:</strong> {{ $book->category ? $book->category->name : 'No Category' }}</p>
                    <p><strong>Tags:</strong> {{ $book->tags }}</p>
                    <p><strong>Published Year:</strong> {{ $book->published_year }}</p>
                    <p><strong>Description:</strong> {{ $book->description }}</p>

                    <p><strong>Available Copies:</strong> {{ $availableCopies }}</p>
                    @auth
                        @if(Auth::user()->role == 'user')
                            @if($availableCopies > 0)
                                @if(!$userHasReserved && !$userHasLoaned)
                                    <form action="{{ route('books.reserve', $book) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-200 px-4 py-2 rounded">Reserve this book</button>
                                    </form>
                                @elseif($userHasReserved)
                                    <form action="{{ route('books.undoReservation', $book) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 px-4 py-2 rounded">Undo Reservation</button>
                                    </form>
                                @endif
                            @else
                                <p class="text-red-700">No available copies to loan or reserve.</p>
                            @endif
                        @endif
                    @endauth

                    @auth
                        @if(auth()->user()->role === 'librarian')
                            <div class="mt-4">
                                <a href="{{ route('books.edit', $book) }}" class="bg-green-500 px-4 py-2 rounded">Edit Book</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 px-4 py-2 rounded" onclick="return confirm('Are you sure you want to delete this book?')">
                                        Delete Book
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div class="mt-6">
                        <h4 class="text-xl font-semibold">Reviews:</h4>
                        @if($book->reviews->count() > 0)
                            <ul class="list-disc pl-5">
                                @foreach($book->reviews as $review)
                                    <li class="{{ $loop->odd ? 'review-color' : 'bg-orange-100' }} p-4 rounded mb-4">
                                        <p>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fa fa-star text-yellow-500"></i>
                                                @else
                                                    <i class="fa fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </p>
                                        <p><strong>Review:</strong> {{ $review->review }}</p>
                                        <p><strong>Reviewed by:</strong> {{ $review->user->name }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No reviews yet for this book.</p>
                        @endif
                    </div>

                    @auth
                        @if(auth()->user()->role == 'user')
                            @php
                                $userReview = $book->reviews()->where('user_id', auth()->id())->first();
                            @endphp

                            <div class="mt-6">
                                <h4 class="text-xl font-semibold">{{ $userReview ? 'Update Your Review' : 'Write a Review' }}:</h4>
                                <form action="{{ route('books.review', $book) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <div id="star-rating" class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star text-gray-300 cursor-pointer" data-value="{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="rating" name="rating" value="{{ $userReview ? $userReview->rating : '' }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="review" class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea id="review" name="review" rows="4" class="mt-1 block w-1/2 p-2 border border-gray-300 rounded" required>{{ $userReview ? $userReview->review : '' }}</textarea>
                                    </div>
                                    <button type="submit" class="button px-4 py-2 rounded">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <style>
        #star-rating .fa-star {
            font-size: 2rem; /* Adjust the size as needed */
            margin-right: 0.25rem; /* Add space between stars */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('#star-rating .fa-star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const rating = this.getAttribute('data-value');
                    ratingInput.value = rating;

                    stars.forEach(s => {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                    });

                    for (let i = 0; i < rating; i++) {
                        stars[i].classList.remove('text-gray-300');
                        stars[i].classList.add('text-yellow-500');
                    }
                });
            });

            // Set initial rating if exists
            const initialRating = ratingInput.value;
            if (initialRating) {
                for (let i = 0; i < initialRating; i++) {
                    stars[i].classList.remove('text-gray-300');
                    stars[i].classList.add('text-yellow-500');
                }
            }
        });
    </script>
</x-app-layout>
