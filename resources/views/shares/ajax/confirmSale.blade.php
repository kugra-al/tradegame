<div class="alert alert-success">
    {{ ucfirst($action) }}ing @if(isset($qty) && $qty){{ $qty }}@else 0 @endif @if($qty>1) shares @else share @endif of company @if(isset($company) && $company){{ $company->id }}@endif on @if(isset($exchange)) exchange {{ $exchange->id }} @endif 
    <br/>
    @if(isset($brought) && sizeof($brought))
        @foreach($brought as $buyPrice=>$buyQty)
            <p>@if($action=='buy') Brought @else Sold @endif {{ $buyQty }} @if($buyQty>1) shares @else share @endif at {{ $buyPrice }}</p>
        @endforeach
    @endif
    @if(isset($placed) && sizeof($placed))
        <p>Placed {{ $placed['qty'] }} @if($placed['qty'] > 1) orders @else order @endif at {{ $placed['price'] }}</p>
    @endif
</div>
