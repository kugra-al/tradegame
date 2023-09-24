@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Owners</div>

                <div class="card-body">

                    @if(isset($owners) && $owners)
                        <table class="table task-table table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Shares</th>
                                <th></th>
                            </thead>
                            <tbody>
                            @foreach($owners as $owner)
                                <tr>
                                    <td>{{ $owner->id }}</td>
                                    <td>{{ $owner->name }}</td>
                                    <td>
                                        @php($shares = $owner->shares)
                                        @if($shares && $shares->count())
                                            <table class="table task-table table-striped">
                                                <thead>
                                                    <th>ID</th>
                                                    <th>Qty</th>
                                                    <th>Company</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                @foreach($shares as $share)
                                                    <tr>
                                                        <td>{{ $share->id }}</td>
                                                        <td>{{ $share->qty }}</td>
                                                        <td>{{ $share->company()->name }}</td>
                                                        <td>@if($share->order())
                                                            {{ $share->order()->price }}
                                                        @endif</td>
                                                        <td>
                                                        @if($share->hasSharesAvaliable())
                                                            <button class="btn btn-success btn-sm" onClick="sellShares({{ $share->company()->id }})">Sell</button>
                                                        @else
                                                            Reserved
                                                        @endif
                                                        </div>
                                                    </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            No shares
                                        @endif
                                    </td>                                
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        No Shares Found
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
