@extends('layouts.app')

@section('css')
.orders .col-sm-4 { float: left; }
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Exchange</div>

                <div class="card-body">
                    @if(isset($exchanges) && $exchanges)
                        @foreach($exchanges as $exchange)
                            {{ $exchange->name }}
                            <hr/>
                                <form class="">
                                    <div class="form-group row">
                                        <label for="company" class="col-sm-2 col-form-label">Company</label>
                                        <div class="col-sm-10">
                                            <select name="company" id="company" class="form-control" 
                                                onchange="return selectCompany(this);">
                                            @foreach($exchange->companies() as $companyOption)
                                                <option value="{{ $companyOption->id }}"
                                                 @if($company->id == $companyOption->id) 
                                                    selected="selected"
                                                 @endif 
                                                 >
                                                    {{ $companyOption->name }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            <div id="orderContent">


                            @if(isset($company) && $company)
                                @include('exchanges.orders.company')
                            @endif
                            </div>
                        @endforeach
                    @else
                        No exchanges found
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
function selectCompany(select) {
    window.location = "/exchanges?c="+$(select).val();
    return false;
}
@endsection
