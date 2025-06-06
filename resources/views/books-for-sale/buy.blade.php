@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Buy: {{ $bookForSale->book->title }}</h2>
        <p><strong>Price:</strong> ${{ number_format($bookForSale->price, 2) }}</p>
        <p><strong>Stock:</strong> {{ $bookForSale->stock }}</p>
        <form action="{{ route('books-for-sale.buy', $bookForSale->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" id="quantity" min="1" max="{{ $bookForSale->stock }}" value="1" class="mt-1 block w-full border rounded" required>
            </div>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                Buy Now
            </button>
        </form>
    </div>
@endsection
