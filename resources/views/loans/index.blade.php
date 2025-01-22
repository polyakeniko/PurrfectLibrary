<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Active Loans
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-xl font-semibold mb-4">Loans List</h3>

                    <form method="GET" action="{{ route('loans.index') }}" class="mb-4">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Search by book title" class="border rounded p-2 mr-2" value="{{ request('search') }}">
                            <select name="returned" class="border rounded p-2 mr-2">
                                <option value="">All</option>
                                <option value="yes" {{ request('returned') === 'yes' ? 'selected' : '' }}>Returned</option>
                                <option value="no" {{ request('returned') === 'no' ? 'selected' : '' }}>Not Returned</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                        </div>
                    </form>

                    @if($loans->count() > 0)
                        <table id="loans-table" class="min-w-full bg-white">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2">Borrower</th>
                                <th class="px-4 py-2">Loan Date</th>
                                <th class="px-4 py-2">Return Due Date</th>
                                <th class="px-4 py-2">Late fee</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td class="border px-4 py-2">{{ $loan->bookCopy->book->title }}</td>
                                    <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $loan->loan_date }}</td>
                                    <td class="border px-4 py-2">{{ $loan->return_due_date ? : 'Not Set' }}</td>
                                    <td class="border px-4 py-2">{{ $loan->late_fee }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($loan->returned_date === null)
                                            <form action="{{ route('loans.return', $loan) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-500 px-4 py-2 rounded w-full hover:bg-red-300 hover:text-red-900">Mark as Returned</button>
                                            </form>
                                        @else
                                            <form action="{{ route('loans.unreturn', $loan) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 px-4 py-2 rounded w-full hover:bg-green-300 hover:text-green-900">Mark as Not Returned</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No active loans currently.</p>
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
            $('#loans-table').DataTable();
        });
    </script>
</x-app-layout>
