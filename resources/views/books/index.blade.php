<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <h3 class="font-semibold text-xl mb-4">Most Popular Books</h3>
                        <ul>
                            @foreach($mostPopularBooks as $book)
                                <li>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-blue-500 hover:underline">
                                        <strong>Title:</strong> {{ $book->title }}
                                    </a>
                                    <br>
                                    <strong>Author:</strong> {{ $book->author }} <br>
                                    <strong>Average Rating:</strong> {{ number_format($book->average_rating, 2) }} <br>
                                </li>
                                <br>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-xl mb-4">Newest Books</h3>
                        <ul>
                            @foreach($newestBooks as $book)
                                <li>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-blue-500 hover:underline">
                                        <strong>Title:</strong> {{ $book->title }}
                                    </a>
                                    <br>
                                    <strong>Author:</strong> {{ $book->author }} <br>
                                    <strong>Published Year:</strong> {{ $book->published_year }} <br>
                                </li>
                                <br>
                            @endforeach
                        </ul>
                    </div>

                    <form method="GET" action="{{ route('books.index') }}">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Search books..." class="border rounded-l px-4 py-2 w-1/2" value="{{ request('search') }}">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r">Search</button>
                        </div>
                    </form>

                    <br>

                    @if($books->count() > 0)
                        <ul>
                            @foreach($books as $book)
                                <li>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-blue-500 hover:underline">
                                        <strong>Title:</strong> {{ $book->title }}
                                    </a>
                                    <br>
                                    <strong>Author:</strong> {{ $book->author }} <br>
                                    <strong>Category:</strong> {{ $book->category ? $book->category->name : 'No Category' }} <br>
                                    <strong>Tags:</strong> {{ $book->tags }} <br>
                                    <strong>Published Year:</strong> {{ $book->published_year }} <br>
                                </li>
                                <br>
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
</x-app-layout>
