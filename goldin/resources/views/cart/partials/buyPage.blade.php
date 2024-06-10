<section>
    <header>
        <h1>Pay</h1>
    </header>
    <button type="button" id="credit_cardBtn" class="btn" aria-label="Pay with credit card">
        <img src="{{ asset('images/credit_card.png')}}" alt="credit_card" width="100" height="100">
    </button>
    <form id="cardDetails" class="mt-4 hideCard" aria-label="Credit card details form">
        <div class="form-group row">
            <label for="cardNumber" class="col-sm-2 col-form-label text-white">Card Number</label>
            <div class="col-sm-10 mb-3">
                <div class="d-flex align-items-center text-white">
                    <input type="text" class="form-control mr-2" id="cardNumber1" placeholder="####" maxlength="4" style="width: 20%;" required>
                    <span>-</span>
                    <input type="text" class="form-control mr-2" id="cardNumber2" placeholder="####" maxlength="4" style="width: 20%;" required>
                    <span>-</span>
                    <input type="text" class="form-control mr-2" id="cardNumber3" placeholder="####" maxlength="4" style="width: 20%;" required>
                    <span>-</span>
                    <input type="text" class="form-control" id="cardNumber4" placeholder="####" maxlength="4" style="width: 20%;">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="cardName" class="col-sm-2 col-form-label text-white">Name on Card</label>
            <div class="col-sm-6">
                <input type="text" class="form-control mb-3" id="cardName" placeholder="Name on Card" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="expiryDate" class="col-sm-2 col-form-label text-white">Expiry Date</label>
            <div class="col-sm-6">
                <input type="text" class="form-control mb-3" id="expiryDate" placeholder="MM/YY" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="cvv" class="col-sm-2 col-form-label text-white">CVV</label>
            <div class="col-sm-6">
                <input type="text" class="form-control mb-3" id="cvv" placeholder="CVV" maxlength="3" required>
            </div>
        </div>
        <div class="mt-2 text-sm text-red-600" id="errorMessage"></div>
        <br>
        <button type="button" id="submitPayment" class="btn btn-primary">Submit Payment</button>
    </form>
</section>
