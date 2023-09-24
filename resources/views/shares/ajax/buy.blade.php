@if(isset($errors))
    @foreach($errors as $error)
        <div class="alert alert-warning">{{ $error }}</div>
    @endforeach
@endif
@if(!isset($company) || !$company)
    <div class="alert alert-error">No Company</div>
@else
    <h4>Buy Shares For {{ $company->name }}</h4>
    <form id="buySharesForm" onsubmit="return submitBuySharesForm()" class="form" novalidate>
        <input type="hidden" name="company_id" value="{{ $company->id }}">
        <input type="hidden" name="exchange_id" value="1">
        <div class="row">
            <div class="col">
                <label>Qty</label>
                <input type="number" class="form-control" name="qty" placeholder="Qty" onclick="$(this).select()" value="1" required>
                <div class="invalid-feedback">
                    Qty Required
                </div>
            </div>
            <div class="col">
                <label>Price</label>
                <input type="number" class="form-control" name="price" placeholder="0.00" onClick="$(this).select()" required value="0.01">
                <div class="invalid-feedback">
                    Price Required
                </div>
            </div>
            <div class="col">
                <label>Exchange</label>
                <input class="form-control" disabled value="1">
            </div>
        </div>
    </form>
@endif
