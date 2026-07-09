@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
 {{-- <img src="{{ asset('assets/images/logo.svg') }}"
         class="logo"
         alt="KaMa Online Library"
         style="max-height:80px;"> --}}

{{-- Test local --}}
<img src="https://github.com/Ornella242/KaMa-Online-Library/blob/main/public/assets/images/logo.svg"
         class="logo"
         alt="KaMa Online Library"
         style="max-height:80px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
