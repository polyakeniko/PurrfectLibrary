<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Book - {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                                <option value="">Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <input type="text" name="tags" id="tags" value="{{ old('tags', $book->tags) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="published_year" class="block text-sm font-medium text-gray-700">Published Year</label>
                            <input type="number" name="published_year" id="published_year" value="{{ old('published_year', $book->published_year) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded">{{ old('description', $book->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        </div>

                        <button type="submit" class="bg-blue-500 px-4 py-2 rounded text-white">Update Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
