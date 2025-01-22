<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Device Detections') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="detections-table" class="display">
                        <thead>
                        <tr>
                            <th>Device</th>
                            <th>Platform</th>
                            <th>Browser</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            <th>Detected At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($detections as $detection)
                            <tr>
                                <td>{{ $detection->device }}</td>
                                <td>{{ $detection->platform }}</td>
                                <td>{{ $detection->browser }}</td>
                                <td>{{ $detection->ip }}</td>
                                <td>{{ $detection->user_agent }}</td>
                                <td>{{ $detection->created_at }}</td>
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
            $('#detections-table').DataTable();
        });
    </script>
</x-app-layout>
