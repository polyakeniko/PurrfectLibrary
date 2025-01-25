<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Qr Codes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="qr-codes-table" class="min-w-full border-collapse border border-gray-200">
                        <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-center">ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Title</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Author</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Description</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Published Year</th>
                            <th class="border border-gray-300 px-5 py-6 text-center">QR Code</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $book->id }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $book->title }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $book->author }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $book->description }}</td>
                                <td class="border border-gray-300 px-4 py-4 text-center">{{ $book->published_year }}</td>
                                <td class="border border-gray-300 text-center">
                                    @if($book->qrCode)
                                        <img src="{{ asset('storage/' . $book->qrCode->qr_code_image) }}" alt="QR Code" class="w-80 h-40">
                                    @else
                                        No QR Code
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <form action="{{ route('qr-codes.store', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button text-white px-4 py-2 rounded">
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
    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#qr-codes-table').DataTable();
        });
    </script>

</x-app-layout>
