<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Library Members</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('members.create') }}" class="button px-4 py-2 rounded text-white mb-4 inline-block">Add New Member</a>

                    <table class="min-w-full bg-white text-left">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ ucfirst($member->status) }}</td>
                                <td>
                                    <a href="{{ route('members.edit', $member) }}" class="text-blue-500">Edit</a>
                                    @if($member->status == 'active')
                                        <form action="{{ route('members.block', $member) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="text-red-500">Block</button>
                                        </form>
                                    @else
                                        <form action="{{ route('members.unblock', $member) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="text-green-500">Unblock</button>
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
