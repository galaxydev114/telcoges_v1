@extends('template.main', ['activePage' => 'user-memberships', 'titlePage' => __('Planes')])

@section('content')
{{--  
  <div class="row">
    <div class="col-md-3">
      <a href="{{ route('organizations.payments') }}">
        <button class="btn btn-warning">{{ __('Ver mis pagos') }}</button>
      </a>
    </div>
  </div>
--}}
  <div class="row mt-3">
    @foreach ( $memberships as $membership )
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card">
          <div class="card-header">
            <div class="card-icon">
              <i class="fa fa-star" style="@if( auth()->user()->subscribedToPlan($membership->stripe_id, 'main') ) color:yellow; @endif"></i>
            </div>
            <h3 class="card-category">{{ $membership->name }}</h3>
          </div>
          <div class="card-block">
            <h3 class="card-title">{{ $membership->price + $membership->iva }}
              <small>@if($membership->frequency == 'anual') &euro;/año @elseif($membership->frequency == 'monthly') &euro;/mes @endif</small>
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-tag"></i>{{ $membership->name }}
            </div>
            @if( !auth()->user()->subscribedToPlan($membership->stripe_id, 'main') )
              <form action="{{ route('subscription.activate') }}" method="post">
                @csrf
                <input type="hidden" name="stripe_id" value="{{ $membership->stripe_id }}">
                <button type="submit" class="btn btn-info">
                  Activar
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
@endsection