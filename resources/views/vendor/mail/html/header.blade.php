@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<tr>
    <td class="header">
        <a href="{{ config('app.url') }}" style="text-decoration: none; color: #333; font-size: 24px;">
            Cidade360
        </a>
    </td>
</tr>@else
{{ $slot }}
@endif
</a>
</td>
</tr>
