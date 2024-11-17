<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reservations Management
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

                    <h3 class="text-xl font-semibold mb-4">Reservations List</h3>

                    @if($reservations->count() > 0)
                        <table class="min-w-full bg-white">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2">Reserved By</th>
                                <th class="px-4 py-2">Reservation Date</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td class="border px-4 py-2">{{ $reservation->bookCopy->book->title }}</td>
                                    <td class="border px-4 py-2">{{ $reservation->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $reservation->reservation_date }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($reservation->status) }}</td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('reservations.updateStatus', $reservation) }}" method="POST">
                                            @csrf
                                            <select name="status" class="border rounded p-2" onchange="this.form.submit()">
                                                <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="ready" {{ $reservation->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="canceled" {{ $reservation->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                                <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No reservations currently.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
