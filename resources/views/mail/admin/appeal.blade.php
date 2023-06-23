<x-mail::message>
## {{ trans('notifications.admin.appeal.line_a', ['user' => $appeal->user->name]) }}

Email: <a href="mailto:{{ $appeal->user->email }}">{{ $appeal->user->email }}</a>

{{ trans('notifications.admin.appeal.line_b') }}
{{ $appeal->message }}

@if($appeal->rating)
<div style="display: flex; gap: 0 2px;">
@for($i = 1; $i <= 5; $i++)
@if($i <= $appeal->rating)
<img src="{{ asset('images/star-filled.svg') }}" alt="" style="display: block; width: 20px; height: 20px;">
@else
<img src="{{ asset('images/star-outline.svg') }}" alt="" style="display: block; width: 20px; height: 20px;">
@endif
@endfor
</div>
@endif
</x-mail::message>
