<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Librarians') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('librarians.create') }}" class="bg-green-500 px-4 py-2 rounded">Add Librarian</a>
                    <table class="min-w-full bg-white border border-gray-300 mt-4">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($librarians as $librarian)
                            <tr>
                                <td class="px-4 py-2 border">{{ $librarian->name }}</td>
                                <td class="px-4 py-2 border">{{ $librarian->email }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('librarians.edit', $librarian) }}" class="bg-blue-500 px-4 py-2 rounded">Edit</a>
                                    @if($librarian->status == 'banned')
                                        <form action="{{ route('librarians.activate', $librarian) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-green-500 px-4 py-2 rounded" onclick="return">Activate</button>
                                        </form>
                                    @else
                                        <form action="{{ route('librarians.destroy', $librarian) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 px-4 py-2 rounded" onclick="return">Ban</button>
                                        </form>
                                    @endif
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
