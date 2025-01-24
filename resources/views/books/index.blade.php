<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-3xl mb-4 text-center mt-5">Our Collection of Books</h2>
                <div class="p-6 text-gray-900">

                    <div class="flex flex-wrap justify-center gap-10 text-white">

                        <div class="mb-6 flex-col p-5 rounded-3xl text-center border-4" style="background-color: #62492c; border-color: #2d2013">
                            <div class="border-4 border-black p-3 rounded-2xl" style="background-color: #916949; border-color: #2d2013">
                                <h3 class="font-semibold text-xl mb-4">Most Popular Books</h3>
                                <hr>
                                <ul>
                                    @foreach($mostPopularBooks as $book)
                                        <li class="mt-4 p-2 rounded-md" style="background-color: #6b4c33">
                                            <a href="{{ route('books.show', $book->id) }}">
                                                <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default.jpg') }}" alt="{{ $book->title }}" class="mt-4 w-full h-48 object-cover rounded-md">
                                            </a>
                                            <a href="{{ route('books.show', $book->id) }}" class="text-orange-200 hover:underline">
                                                <strong>Title:</strong> {{ $book->title }}
                                            </a>
                                            <br>
                                            <strong>Author:</strong> {{ $book->author }} <br>
                                            <strong>Average Rating:</strong> {{ number_format($book->average_rating, 2) }} <br>
                                        </li>
                                        <br>
                                        <hr>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                        <div class="mb-6 flex-col p-5 rounded-3xl text-center border-4" style="background-color: #62492c; border-color: #2d2013">
                            <div class="border-4 border-black p-3 rounded-2xl" style="background-color: #916949; border-color: #2d2013">
                                <h3 class="font-semibold text-xl mb-4">Newest Books</h3>
                                <hr>
                                <ul>
                                    @foreach($newestBooks as $book)
                                        <li class="mt-4 p-2 rounded-md" style="background-color: #6b4c33">
                                            <a href="{{ route('books.show', $book->id) }}">
                                                <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default.jpg') }}" alt="{{ $book->title }}" class="mt-4 w-full h-48 object-cover rounded-md">
                                            </a>
                                            <a href="{{ route('books.show', $book->id) }}" class="text-orange-200 hover:underline">
                                                <strong>Title:</strong> {{ $book->title }}
                                            </a>
                                            <br>
                                            <strong>Author:</strong> {{ $book->author }} <br>
                                            <strong>Published Year:</strong> {{ $book->published_year }} <br>
                                        </li>
                                        <br>
                                        <hr>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>

                    <form method="GET" action="{{ route('books.index') }}">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Search books..." class="border rounded-l-md px-4 py-3 w-1/4" value="{{ request('search') }}">
                            <button type="submit" class="button text-white px-4 py-3.5 rounded-r-md">Search</button>
                        </div>
                    </form>

                    <br>
                    <div>
                        @if($books->count() > 0)
                            <ul class="flex flex-wrap gap-4 justify-center">
                                @foreach($books as $book)
                                    <li class="w-1/4 p-4 border rounded shadow text-white text-center"
                                        style="background-color: {{ $loop->index % 2 == 0 ? '#916949' : '#6b4c33' }};">
                                        <a href="{{ route('books.show', $book->id) }}">
                                            <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default.jpg') }}" alt="{{ $book->title }}" class="mt-4 w-full h-48 object-cover rounded-md">
                                        </a>
                                        <a href="{{ route('books.show', $book->id) }}" class="text-orange-200 hover:underline">
                                            <strong>Title:</strong> {{ $book->title }}
                                        </a>
                                        <br>
                                        <strong>Author:</strong> {{ $book->author }} <br>
                                        <strong>Category:</strong> {{ $book->category ? $book->category->name : 'No Category' }} <br>
                                        <strong>Tags:</strong> {{ $book->tags }} <br>
                                        <strong>Published Year:</strong> {{ $book->published_year }} <br>
                                    </li>
                                @endforeach
                            </ul>
                            {{ $books->links() }}
                        @else
                            <p>No books found.</p>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
