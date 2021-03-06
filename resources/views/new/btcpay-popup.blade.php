@auth()
    <div id="btcpay-popup" class="offcanvas offcanvas-start" tabindex="-1" aria-labelledby="btcpay-popup">
        <div class="offcanvas-body">
            <h3 class="no-margin-bottom">RECHARGING VIA BTCPAY (LTC)</h3>
            <span>
                <?php
                $coinPayment = \App\Option::where('option', 'coinpayment_bonus')->first();
                ?>
                @if(isset($coinPayment) && ($coinPayment->value !== '0'))
                    (Get '  {{ $coinPayment->value }} '% more)
                @endif
            </span>
            <form action="#">
                <div class="row mt-3">
                    <div class="input-field">
                        <div for="amountbtc">Amount
                            <span class="helper-text" id="required_message" style="display: none; color: red" data-error="wrong" data-success="right">Please enter amount</span>
                        </div>
                        <input type="number" class="validate w-50" type="number" name="amountbtc" id="amountbtc">

                    </div>
                    <label>Currency</label>
                    <div class="col-12">
                        <select class="browser-default w-100" id="currency">
                            @foreach (config('const.coin_currencies') as $currency)
                                <option value="{{$currency}}">{{$currency}}</option>
                            @endforeach
                        </select>
                    </div>

                    <br>
                    <div class="input-field mt-3 mb-3">
                        <button type="button" class="btn btn-primary"
                            onclick="rechargeBtcpayPayments()">
                        Recharge now
                    </button>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8">
                        <h4>Tutorial</h4>
                        <p>1，Input the values and select coin you want recharge</p>
                        <p>2，Payment to order display address</p>
                        <p>3，Waiting for payment confirmation BTC will take time, depends on your
                            bitcoin wallet.</p>
                        <p>4，Please calculate the handling fee required for the transfer (the
                            exchange
                            will charge a fee)！</p>
                        <p>5，You need to make sure the actual arrival amount is equal to or greater
                            than
                            the order amount.</p>
                    </div>
                </div>    
            </form>


        </div>
    </div>
    <script !src="">
        function rechargeBtcpayPayments() {
            let amount = $("#amountbtc").val();
            if (amount !== null && amount !== "") {
                window.open('/create-transaction-btcpay?amount=' + amount, '_blank');
            } else {
                Swal.fire({
                    allowEscapeKey: true,
                    icon : 'error',
                    text: 'Please enter amount'
                });
            }
        }
    </script>
@endauth