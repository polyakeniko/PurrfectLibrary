<x-mail::message>
    # Book Return Due Date Approaching

    Dear {{ $loan->user->name }},

    The return due date for the borrowed book is approaching: {{ $loan->return_due_date }}.

    Please remember to return the book on time.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
