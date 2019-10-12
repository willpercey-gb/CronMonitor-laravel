@component('mail::message')
    # A Cron Failure was recorded

    {{$message}}

    {{--@component('mail::button', ['url' => ''])--}}
    {{--Button Text--}}
    {{--@endcomponent--}}

    For more information visit your account,<br>
    {{ config('app.name') }}
@endcomponent
