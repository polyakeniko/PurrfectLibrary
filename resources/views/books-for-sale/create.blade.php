<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Book For Sale
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form action="{{ route('books-for-sale.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="book_id" class="block text-sm font-medium text-gray-700">Book</label>
                        <select name="book_id" id="book_id" class="mt-1 block w-full border rounded" required>
                            <option value="">Select a Book</option>@foreach($books as $book)
                                @php
                                    $activeSale = $book->bookForSale()->where('is_active', true)->first();
                                @endphp
                                <option value="{{ $book->id }}" @if($activeSale) disabled @endif>
                                    {{ $book->title }}
                                    @if($activeSale)
                                        (Already for sale: ${{ number_format($activeSale->price, 2) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" step="0.01" class="mt-1 block w-full border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" name="stock" id="stock" class="mt-1 block w-full border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="ml-2">Active</span>
                        </label>
                    </div>
                    <button type="submit" class="button px-4 py-2 rounded text-white">Add Book For Sale</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
