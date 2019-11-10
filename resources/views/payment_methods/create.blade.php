@extends('template.main', ['activePage' => 'memberships', 'titlePage' => __('Agregar metodo de pago')])

@section('content')
<div class="col-md-3">
</div>

<div class="card col-md-6">
  <input id="card-holder-name" type="text">
  <!-- Stripe Elements Placeholder -->
  <div id="card-element"></div>
  <button id="card-button" data-secret="{{ $intent->client_secret }}">
      Agregar metodo de pago
  </button>
</div>

<div class="col-md-3">
</div>

<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <script src="https://js.stripe.com/v3/"></script>

  <script>
    const stripe = Stripe('pk_test_51H8NBFKsqucbSbacf40MQl8E0UdFuCLYBdmVjEvnCbzHbfUyYMcdIDYAcnPJAyFAytTWtr1R6yspoJumKckL6kG100E1sDIhvm');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
          // Display "error.message" to the user...
          alert('Ocurrio un error, intente más tarde');
        } else {
            if ( setupIntent.status == 'succeeded') {
              let url = '/admin/public/payment/method/store/'+setupIntent.payment_method;
              let token = document.getElementById('token').getAttribute('value');
              fetch(url, {
                headers: {
                  "Content-Type": "application/json",
                  "Accept": "application/json, text-plain, */*",
                  "X-Requested-With": "XMLHttpRequest",
                  "X-CSRF-TOKEN": token
                },
                method: 'post',
                credentials: "same-origin",
              })
              .then((data) => {
                console.log(data);
                window.location = '/admin/public/payment/methods';
              })
              .catch(function(error) {
                alert(error);
              });
            }
        }
    });
  </script>
@endsection