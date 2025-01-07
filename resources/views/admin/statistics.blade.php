<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-700">Library Statistics</h3>
                    <ul class="mt-4">
                        <li>Total Books: {{ $totalBooks }}</li>
                        <li>Total Members: {{ $totalMembers }}</li>
                        <li>Total Loans: {{ $totalLoans }}</li>
                        <li>Total Reservations: {{ $totalReservations }}</li>
                        <li>Overdue Loans: {{ $overdueLoans }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
