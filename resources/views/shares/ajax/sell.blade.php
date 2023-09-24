@if(isset($errors))
    @foreach($errors as $error)
        <div class="alert alert-warning">{{ $error }}</div>
    @endforeach
@endif

@if(!isset($shares) || !$shares)
    <div class="alert alert-warning">No shares to sell</div>
@else
    <form id="sellSharesForm" onsubmit="return submitSellSharesForm()" class="form" novalidate>
        <input type="hidden" name="share_id" value="{{ $shares->id }}">
        <input type="hidden" name="company_id" value="{{ $shares->company_id }}">
        <input type="hidden" name="exchange_id" value="1">
        @if($shares)
            <strong>Shares Avaliable</strong> <a href="#" onclick="return setOrderQty({{ $shares->qty }})">{{ $shares->qty }}</a><br/>
        @endif
        <div class="row">
            <div class="col">
                <label>Qty</label>
                <input type="number" class="form-control" name="qty" placeholder="Qty" value="1" onclick="$(this).select()" required>
                <div class="invalid-feedback">
                    Qty Required
                </div>
            </div>
            <div class="col">
                <label>Price</label>
                <input type="number" class="form-control" name="price" value="10.00" onClick="$(this).select()" required>
                <div class="invalid-feedback">
                    Price Required
                </div>
            </div>
            <div class="col">
                <label>Exchange</label>
                <span>1</span>
            </div>
        </div>
    </form>
@endif

