<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Genius Exams')
<img src="{{asset('/images/logo.png')}}" class="logo" alt="{{ config('app.name') }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
