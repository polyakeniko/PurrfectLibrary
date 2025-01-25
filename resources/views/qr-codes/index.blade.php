<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Books List with QR Code Generation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Title</th>
                            <th class="border border-gray-300 px-4 py-2">Author</th>
                            <th class="border border-gray-300 px-4 py-2">Description</th>
                            <th class="border border-gray-300 px-4 py-2">Published Year</th>
                            <th class="border border-gray-300 px-5 py-6">QR Code</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $book->id }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $book->title }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $book->author }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $book->description }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $book->published_year }}</td>
                                <td class="border border-gray-300">
                                    @if($book->qrCode)
                                        <img src="{{ asset('storage/' . $book->qrCode->qr_code_image) }}" alt="QR Code" class="w-40 h-35 mx-auto">
                                    @else
                                        No QR Code
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <form action="{{ route('qr-codes.store', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                            Generate / Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
