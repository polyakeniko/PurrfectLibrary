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
                    <h3 class="text-2xl font-semibold">Title: {{ $book->title }}</h3>
                    <p><strong>Author:</strong> {{ $book->author }}</p>
                    <p><strong>Category:</strong> {{ $book->category ? $book->category->name : 'No Category' }}</p>
                    <p><strong>Tags:</strong> {{ $book->tags }}</p>
                    <p><strong>Published Year:</strong> {{ $book->published_year }}</p>
                    <p><strong>Description:</strong> {{ $book->description }}</p>

                    <p><strong>Available Copies:</strong> {{ $availableCopies }}</p>
                    @if(Auth::user()->role == 'user')
                    @auth
                        @if($availableCopies > 0)
                            @if(!$userHasReserved && !$userHasLoaned)
                                <form action="{{ route('books.reserve', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 px-4 py-2 rounded">Reserve this book</button>
                                </form>
                            @elseif($userHasReserved)
                                <form action="{{ route('books.undoReservation', $book) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 px-4 py-2 rounded">Undo Reservation</button>
                                </form>
                            @endif
                        @else
                            <p>No available copies to loan or reserve.</p>
                        @endif
                    @else
                        <p><a href="{{ route('login') }}" class="text-blue-500">Login to reserve this book</a></p>
                    @endauth
                    @endif

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
                                    <li>
                                        <p><strong>Rating:</strong> {{ $review->rating }}</p>
                                        <p><strong>Review:</strong> {{ $review->review }}</p>
                                        <p><strong>Reviewed by:</strong> {{ $review->user->name }}</p>
                                    </li>
                                    <br>
                                @endforeach
                            </ul>
                        @else
                            <p>No reviews yet for this book.</p>
                        @endif
                    </div>

                    @if(Auth::user()->role == 'user')
                    @auth
                        @php
                            $userReview = $book->reviews()->where('user_id', auth()->id())->first();
                        @endphp

                        @if($userReview)
                            <div class="mt-6">
                                <h4 class="text-xl font-semibold">Update Your Review:</h4>
                                <form action="{{ route('books.review', $book) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <select id="rating" name="rating" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                                            <option value="1" {{ $userReview->rating == 1 ? 'selected' : '' }}>1 - Poor</option>
                                            <option value="2" {{ $userReview->rating == 2 ? 'selected' : '' }}>2 - Fair</option>
                                            <option value="3" {{ $userReview->rating == 3 ? 'selected' : '' }}>3 - Good</option>
                                            <option value="4" {{ $userReview->rating == 4 ? 'selected' : '' }}>4 - Very Good</option>
                                            <option value="5" {{ $userReview->rating == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="review" class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea id="review" name="review" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>{{ $userReview->review }}</textarea>
                                    </div>
                                    <button type="submit" class="bg-blue-500 px-4 py-2 rounded">Update Review</button>
                                </form>
                            </div>
                        @else
                            <div class="mt-6">
                                <h4 class="text-xl font-semibold">Write a Review:</h4>
                                <form action="{{ route('books.review', $book) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <select id="rating" name="rating" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                                            <option value="1">1 - Poor</option>
                                            <option value="2">2 - Fair</option>
                                            <option value="3">3 - Good</option>
                                            <option value="4">4 - Very Good</option>
                                            <option value="5">5 - Excellent</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="review" class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea id="review" name="review" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded" required></textarea>
                                    </div>
                                    <button type="submit" class="bg-blue-500 px-4 py-2 rounded">Submit Review</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <p><a href="{{ route('login') }}" class="text-blue-500">Login to write a review</a></p>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
