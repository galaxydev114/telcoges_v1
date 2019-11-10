<p>
	Hola {{ $user->name }}, para completar tu registro es necesario que verifiques tu identidad a través del siguiente <a href="{{ route('email.verify', ['email' => $user->email,'token' => $user->verify_token]) }}">enlace</a>.
</p>