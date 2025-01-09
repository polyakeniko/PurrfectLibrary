<x-mail::message>
    # New Book Added to the Library

    A new book titled "{{ $book->title }}" by {{ $book->author }} has been added to the library.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
