@extends('dashboards.user.userDashboard')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold" style="font-size: 1.17rem;">
                    <i class="fas fa-wallet text-orange me-2"></i> Choose a Payment Method
                </div>
                <div class="card-body">

                    <form id="paymentForm" >
                        @csrf

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label fw-semibold" for="cod">
                                    <i class="fas fa-money-bill-wave me-1 text-success"></i> Cash on Delivery
                                </label>
                                <div class="small text-muted ms-4">
                                    Pay when you receive the product.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 border-top pt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                                <label class="form-check-label fw-semibold" for="card">
                                    <i class="fas fa-credit-card me-1 text-primary"></i> Credit/Debit Card
                                </label>
                            </div>
                            <div id="cardDetails" class="ms-4 mt-3" style="display: none;">
                                <div class="mb-2">
                                    <input type="text" class="form-control" placeholder="Card Number" maxlength="19" name="card_number">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="MM/YY" maxlength="5" name="card_expiry">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="CVC" maxlength="4" name="card_cvc">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 border-top pt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="easypaisa" value="easypaisa">
                                <label class="form-check-label fw-semibold" for="easypaisa">
                                     Easypaisa Wallet
                                </label>
                            </div>
                        </div>

                        <div class="mb-3 border-top pt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="jazzcash" value="jazzcash">
                                <label class="form-check-label fw-semibold" for="jazzcash">
                                    JazzCash Wallet
                                </label>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td>Order Amount</td>
                                    <td class="text-end fw-bold">Rs {{ session('order_amount') ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td class="text-end">Rs {{ session('shipping_amount') ?? '100.00' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total</td>
                                    <td class="fw-bolder text-success text-end" style="font-size:1.10rem;">Rs {{ session('order_total') ?? '0.00' }}</td>
                                </tr>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-orange w-100 py-2 mt-3 fw-bold">Place Order &amp; Pay</button>
                    </form>

                    <div class="alert alert-info mt-3 mb-0" style="font-size:0.93rem;">
                        <i class="fas fa-shield-alt me-1"></i> Your payment is processed securely. We do not store your card or wallet details.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('cardDetails');
    paymentRadios.forEach(r => {
        r.addEventListener('change', function() {
            if(this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });
</script>
@endsection
    