<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Sale - {{ $bookForSale->book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('books-for-sale.update', $bookForSale->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4">
                                <div class="text-red-600">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $bookForSale->price) }}" step="0.01" min="0" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $bookForSale->stock) }}" min="0" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Active</label>
                            <select name="is_active" id="is_active" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                                <option value="1" {{ $bookForSale->is_active ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$bookForSale->is_active ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <button type="submit" class="button px-4 py-2 rounded text-white">Update Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
