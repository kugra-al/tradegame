@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Companies</div>

                <div class="card-body">

                    @if(isset($companies) && $companies)
                        <table class="table task-table table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Max Shares</th>
                                <th>Shares</th>
                                <th></th>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->id }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->owner()->name }}</td>
                                    <td>{{ $company->max_shares }}</td>
                                    <td>
                                        @php($shares = $company->shares())
                                        @if($shares && $shares->count())
                                            @foreach($shares as $share)
                                                {{ json_encode($share) }}<br/>
                                            @endforeach
                                        @else
                                            No valid shares found
                                        @endif
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        No Companies Found
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
