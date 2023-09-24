<h4>{{ $company->name }}</h4>
@php($user = Auth::user())
@php($shares = null)
@php($account = null)
@if($user)
    @php($shares = App\Share::where('company_id',$company->id)->where('owner_id',$user->owner()->id)->whereNull('exchange_id')->first())
    @php($account = App\BankAccount::where('owner_id',$user->owner()->id)->whereNull('company_id')->first())
@endif
<script>
    var orders = {'buys':[],'sells':[]};
</script>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-6 left">
            <div class="card card-orders">
                <div class="card-header">
                    <h4>Buy</h4>
                </div>
                <div class="card-body">
                    <div >Cash: @if($account){{ $account->balance }}@else 0 @endif</div>
                    <form class="form orderForm" id="buyOrdersForm" onsubmit="return submitBuyOrdersForm(this)">
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <input type="hidden" name="exchange_id" value="{{ $exchange->id }}">
                        <input type="hidden" name="company_name" value="{{ $company->name }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Price</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="qty" value="1">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Total</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="total" disabled>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-success right">Place Buy Order</button>
                            </div>
                        </div>
                    </form>
                    <h4>Sell Orders</h4>
                    @php($orders = $exchange->getSellOrders($company->id))
                    <script>
                        orders['buys'] = {!! json_encode($orders) !!};
                    </script>
                    @php($action="sell")
                    @include('exchanges.orders.table')
                </div>
            </div>
        </div>
        <div class="col-sm-6 right">
            <div class="card card-orders">
                <div class="card-header">
                    <h4>Sell</h4>
                </div>
                <div class="card-body">
                    <div>Shares: @if($shares){{ $shares->qty }}@else 0 @endif</div>
                    <form class="form orderForm" id="sellOrdersForm" onsubmit="return submitSellOrdersForm(this)"> 
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <input type="hidden" name="exchange_id" value="{{ $exchange->id }}">
                        <input type="hidden" name="company_name" value="{{ $company->name }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Price</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="qty" value="1">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Total</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="total" disabled>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-success right">Place Order</button>
                            </div>
                        </div>
                    </form>
                    <h4>Buy Orders</h4>
                    @php($orders = $exchange->getBuyOrders($company->id))
                    <script>
                        orders['sells'] = {!! json_encode($orders) !!};
                    </script>
                    @php($action="buy")
                    @include('exchanges.orders.table')
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var buyForm = $('#buyOrdersForm');
        var sellForm = $('#sellOrdersForm');

        var topBuy = 0.00;
        var topSell = 0.00;

        if (orders['sells'].length)
            topBuy = orders['sells'][0]['price'];
        if (orders['buys'].length)
            topSell= orders['buys'][orders['buys'].length-1]['price'];

        $(buyForm).find('[name=price]').val(topSell);
        $(sellForm).find('[name=price]').val(topBuy);
        calculateTotalOrder('buy');
        calculateTotalOrder('sell');

        // Change total to the correct num
        var types = ['buy','sell'];
        $.each(types,function(i,v){
            var form = $('#'+v+'OrdersForm');
            var inputs = ['price','qty'];
            $.each(inputs,function(k,p){
                var input = $(form).find("[name="+p+"]");
                console.log(input);
                $(input).on("change",function(){
                    console.log('k');
                    calculateTotalOrder(v);
                });
            });
        });
        console.log('ready fired');
    });

    function calculateTotalOrder(type) {
        var form = $('#'+type+"OrdersForm");

        var total = $(form).find("[name=total]");
        var price = $(form).find("[name=price]");
        var qty = $(form).find("[name=qty]");
        $(total).val($(price).val()*$(qty).val());
        console.log('calcing '+type);
    }
</script>
