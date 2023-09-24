@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Shares</div>

                <div class="card-body">
                    <h4>Shares</h4>

                    @if(isset($shares) && $shares)
                        <table class="table task-table table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Qty</th>
                                <th>Owners</th>
                                <th></th>
                            </thead>
                            <tbody>
                            @foreach($shares as $share)
                                <tr>
                                    <td>{{ $share->id }}</td>
                                    <td>{{ $share->company()->name }}</td>
                                    <td>{{ $share->qty }}</td>
                                    <td>
                                        @php($owner = $share->owner)
                                        @if($owner)
                                            {{ json_encode($owner) }}
                                        @else
                                            No shares
                                        @endif      
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                        @if($share->hasSharesAvaliable())
                                            <button class="btn btn-success" onClick="loadModal()">Buy</button>
                                        @endif
                                        </div>
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
