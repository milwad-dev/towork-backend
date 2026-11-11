<x-mail::message>
# {{ $task->title }}

This is your reminder for the task above.

@if ($task->due_date)
**Due:** {{ $task->due_date->format('l, F j') }}@if ($task->due_time) at {{ substr($task->due_time, 0, 5) }}@endif

@endif
@if ($task->description)
<x-mail::panel>
{{ \Illuminate\Support\Str::limit($task->description, 400) }}
</x-mail::panel>
@endif

<x-mail::button :url="$url">
Open task
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
