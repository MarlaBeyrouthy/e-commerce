{{--please put the code : "{{$resetCode}}"in the field of the confirmation code then you can reset your password.
you have 30 minutes before the code expired.--}}

<p>Dear {{ $user->name }},</p>

<p>Please use the following code to reset your password:</p>

<p style="font-size: 24px; font-weight: bold;">{{ $resetCode }}</p>

<p>You have 30 minutes to use this code before it expires.</p>

<p>Thank you for using our app!</p>
