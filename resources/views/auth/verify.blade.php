@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'title' => __('Material Dashboard'), 'titlePage' => ''])

@section('content')
<div class="container" style="height: auto;">
  <div class="row justify-content-center">
      <div class="col-lg-7 col-md-8">
          <div class="card card-login card-hidden mb-3">
            <div class="card-header card-header-info text-center">
              <p class="card-title"><strong>{{ __('Verifica tu email') }}</strong></p>
            </div>
            <div class="card-body">
              <p class="card-description text-center"></p>
              <p>
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('Hemos enviado un nuevo email, por favor verifica.') }}
                    </div>
                @endif
                
                {{ __('Antes de continuar, hemos enviado un enlace de verificación a tu email.') }}
                
                @if (Route::has('verification.resend'))
                    {{ __('Si no has recibido el email') }},  
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click aquí para solicitar otro enlace') }}</button>.
                    </form>
                @endif
              </p>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
