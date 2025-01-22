<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Borrowed Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($loans->isEmpty())
                        <p>You have no borrowed books at the moment.</p>
                    @else
                        <table id="borrowed-books-table" class="min-w-full bg-white border border-gray-300">
                            <thead>
                            <tr>
                                <th class="px-4 py-2 border">Book Title</th>
                                <th class="px-4 py-2 border">Loan Date</th>
                                <th class="px-4 py-2 border">Due Date</th>
                                <th class="px-4 py-2 border">Returned Date</th>
                                <th class="px-4 py-2 border">Late Fee</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $loan->bookCopy->book->title }}</td>
                                    <td class="px-4 py-2 border">{{ $loan->loan_date }}</td>
                                    <td class="px-4 py-2 border">{{ $loan->return_due_date ? $loan->return_due_date : 'N/A' }}</td>
                                    <td class="px-4 py-2 border">{{ $loan->returned_date ? $loan->returned_date : 'Not yet returned' }}</td>
                                    <td class="px-4 py-2 border">{{ $loan->late_fee > 0 ? '€' . number_format($loan->late_fee, 2) : 'No late fee' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#borrowed-books-table').DataTable();
        });
    </script>
</x-app-layout>
