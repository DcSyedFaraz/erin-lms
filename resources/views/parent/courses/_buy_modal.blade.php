<div class="modal fade" id="buyCourseModal" tabindex="-1" aria-labelledby="buyCourseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buyCourseLabel">Buy Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <strong id="buy-course-title">Course Title</strong>
            <div class="text-muted">Price: <span id="buy-course-price">$0.00</span></div>
        </div>
        <div id="card-element" class="form-control p-2"></div>
        <div id="card-errors" class="invalid-feedback d-block"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="confirm-purchase" class="btn btn-primary">
            <i class="fas fa-credit-card mr-1"></i> Pay Now
        </button>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
  (function() {
    const publishableKey = @json(config('services.stripe.key'));
    if (!publishableKey) {
        console.warn('Stripe publishable key missing. Set STRIPE_KEY in .env');
        return;
    }
    const stripe = Stripe(publishableKey);
    let elements = null;
    let card = null;

    let selectedCourseId = null;
    let purchaseId = null;

    function mountElements() {
        if (elements) return;
        elements = stripe.elements();
        card = elements.create('card', { hidePostalCode: true });
        card.mount('#card-element');
        card.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });
    }

    $(document).on('click', '.btn-buy-modal', function() {
        const btn = $(this);
        selectedCourseId = btn.data('course-id');
        const title = btn.data('course-title');
        const price = btn.data('course-price');
        $('#buy-course-title').text(title);
        $('#buy-course-price').text(price);
        $('#card-errors').text('');
        mountElements();
        $('#buyCourseModal').modal('show');
    });

    $('#confirm-purchase').on('click', async function() {
        if (!selectedCourseId) return;
        const csrf = $('meta[name="csrf-token"]').attr('content');
        try {
            // Create PaymentIntent
            const intentRes = await fetch(`/parent/courses/${selectedCourseId}/intent`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({})
            });
            const intentData = await intentRes.json();
            if (!intentRes.ok) {
                toastr.error(intentData.message || 'Could not start payment');
                return;
            }
            purchaseId = intentData.purchase_id;

            // Confirm payment with card
            const {error, paymentIntent} = await stripe.confirmCardPayment(intentData.client_secret, {
                payment_method: {
                    card: card,
                }
            });
            if (error) {
                toastr.error(error.message || 'Payment was not completed');
                return;
            }

            // Notify server to complete
            const completeRes = await fetch(`/parent/courses/${selectedCourseId}/complete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ payment_intent_id: paymentIntent.id, purchase_id: purchaseId })
            });
            const completeData = await completeRes.json();
            if (!completeRes.ok) {
                toastr.error(completeData.message || 'Payment verification failed');
                return;
            }

            toastr.success('Payment successful!');
            $('#buyCourseModal').modal('hide');
            window.location.href = `{{ route('parent.courses.my') }}`;
        } catch (e) {
            console.error(e);
            toastr.error('Unexpected error during payment');
        }
    });
  })();
</script>
@endsection

