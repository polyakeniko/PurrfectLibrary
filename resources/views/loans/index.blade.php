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

                    @if($loans->count() > 0)
                        <table class="min-w-full bg-white">
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
                                    <td class="border px-4 py-2">{{ $loan->late_fee}}</td>
                                    <td class="border px-4 py-2">
                                        @if ($loan->returned_date === null)
                                        <form action="{{ route('loans.return', $loan) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 px-4 py-2 rounded">Mark as Returned</button>
                                        </form>
                                        @else
                                            <form action="{{ route('loans.unreturn', $loan) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 px-4 py-2 rounded">Mark as Not Returned</button>
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
</x-app-layout>
