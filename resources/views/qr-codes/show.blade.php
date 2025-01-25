<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            QR Code for {{ $book->title }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div>{!! $qrCode !!}</div>
        <a href="{{ route('qr-codes.index') }}" class="text-blue-500 underline">Back to list</a>
    </div>
</x-app-layout>
