<x-mail::message>
# Export Success

The export has been processed successfully.

## To show details please click the following link:

<x-mail::button :url="$showExportsUrl">
    Show exports
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
