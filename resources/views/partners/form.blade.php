<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Become a Partner
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('partners.register') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Your Website</label>
                            <input type="url" name="website" class="block w-full border rounded p-2" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
