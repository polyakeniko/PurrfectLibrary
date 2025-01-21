<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin_settings.update', $settings->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="library_open_time" class="block text-sm font-medium text-gray-700">Library Open Time</label>
                            <input type="text" name="library_open_time" id="library_open_time" value="{{ old('library_open_time', $settings->library_open_time) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="library_close_time" class="block text-sm font-medium text-gray-700">Library Close Time</label>
                            <input type="text" name="library_close_time" id="library_close_time" value="{{ old('library_close_time', $settings->library_close_time) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded">{{ old('description', $settings->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="max_books_to_loan" class="block text-sm font-medium text-gray-700">Max Books to Loan</label>
                            <input type="number" name="max_books_to_loan" id="max_books_to_loan" value="{{ old('max_books_to_loan', $settings->max_books_to_loan) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <button type="submit" class="bg-blue-500 px-4 py-2 rounded text-white">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
