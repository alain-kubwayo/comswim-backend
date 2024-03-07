@component('mail::message')
    <p>Hello {{ $user->first_name }}</p>    
    @component('mail::button', ['url' => url('verify-email/'.$user->remember_token)])
        <p>Verify</p>    
    @endcomponent
    Thanks <br>
    {{ env('APP_NAME') }}
@endcomponent