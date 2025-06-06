<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, Partner!
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2">Your API Key:</p>
                    <div class="bg-gray-100 p-2 rounded font-mono mb-4">{{ $apiKey }}</div>
                    <p>Use this key to access our free APIs and sell our books on your website.</p>
                    <a href="/api-docs" class="text-blue-600 underline">View API Documentation</a>
                    <form action="{{ route('partners.regenerateApiKey') }}" method="GET" class="mt-4">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Regenerate API Key</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
