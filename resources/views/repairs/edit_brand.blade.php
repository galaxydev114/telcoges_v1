@extends('template.main', ['activePage' => 'repair-brands', 'titlePage' => __('Editar marca')])

@section('content')
  <form class="form" method="POST" action="{{ route('repairs.brands.update', [$brand->id]) }}">
    @csrf
    @method('put')

    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-building"></i>
          </span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre de la marca...') }}" value="{{ $brand->name }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>

    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Actualizar') }}</button>
    </div>
    </div>
  </form>

  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/provider/search" @elseif(config('app.env') === 'production') "/admin/public/provider/search" @endif>
@endsection