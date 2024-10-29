<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('ขออภัย!')
@else
# @lang('สวัสดี!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ __('คุณได้รับอีเมลนี้เนื่องจากมีการร้องขอให้รีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ') }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
{{ __('ลิงก์สำหรับรีเซ็ตรหัสผ่านนี้จะหมดอายุภายใน 60 นาที') }}
@foreach ($outroLines as $line)
@endforeach
{{-- Subcopy --}}
{{ __('หากท่านไม่ได้ขอรีเซ็ตรหัสผ่านนี้ ไม่จำเป็นต้องดำเนินการเพิ่มเติม') }}

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}   
@else
@lang('ด้วยความนับถือ'),
{{ config('app.name') }}
@endif

@isset($actionText)
<x-slot:subcopy>
@lang(
    "หากท่านประสบปัญหาในการดำเนินงานผ่านทางการกดปุ่ม \":actionText\" กรุณาคัดลอกและวาง URL ด้านล่างนี้\n".
    'ในเว็บเบราว์เซอร์ของท่าน :',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
