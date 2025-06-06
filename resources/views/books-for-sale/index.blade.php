<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-3xl mb-4 text-center mt-5">Books for Sale</h2>
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-end">
                        <a href="{{ route('books-for-sale.create') }}"
                           class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                            Add Book For Sale
                        </a>
                        <a href="{{ route('cart.show') }}" class="ml-4">Cart</a>
                    </div>
                    <ul class="flex flex-wrap gap-4 justify-center">
                        @foreach($booksForSale as $item)
                            <li class="w-1/4 p-4 border rounded shadow text-white text-center"
                                style="background-color: {{ $loop->index % 2 == 0 ? '#916949' : '#6b4c33' }};">
                                <a href="{{ route('books.show', $item->book->id) }}">
                                    <img src="{{ $item->book->image ? asset('storage/' . $item->book->image) : asset('images/default.jpg') }}" alt="{{ $item->book->title }}" class="mt-4 w-full h-48 object-cover rounded-md">
                                </a>
                                <a href="{{ route('books.show', $item->book->id) }}" class="text-orange-200 hover:underline">
                                    <strong>Title:</strong> {{ $item->book->title }}
                                </a>
                                <br>
                                <strong>Author:</strong> {{ $item->book->author }} <br>
                                <strong>Price:</strong> ${{ number_format($item->price, 2) }} <br>
                                <strong>Stock:</strong> {{ $item->stock }} <br>
                            </li>
                        @endforeach
                    </ul>
                    @if($booksForSale->isEmpty())
                        <p>No books for sale found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
