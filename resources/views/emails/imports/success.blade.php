<x-mail::message>
# Import Success

The import has been processed successfully.

## To show details please click the following link:

<x-mail::button :url="$showImportsUrl">
Show imports
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
