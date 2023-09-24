<nav class="navbar navbar-expand-sm navbar-dark bg-dark" style="color:#FFF;height:20px;">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if(Auth::user())
                    @php($owner = Auth::user()->owner())
                    @if($owner)
                        @php($account = \App\BankAccount::where('owner_id',$owner->id)->first())
                        <li class="nav-item"><strong>{{ $owner->name }} Balance: </strong> {{ $account->balance }}</li>
                        @php($company = $owner->company())
                        @php($account = \App\BankAccount::where('company_id',$company->id)->first())
                        <li class="nav-item"><strong>{{ $company->name }} Balance: </strong> {{ $account->balance }}</li>                    
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>