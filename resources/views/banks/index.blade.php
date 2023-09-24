@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Bank</div>

                <div class="card-body">
                    <div class="col-sm-6 left">
                        @if(isset($owner) && $ownerAccount)
                            <h4>{{ $owner->name }} Account</h4>
                            <div class="form-group">
                                <label>Balance</label>
                                <div>{{ $ownerAccount->balance }}</div>
                            </div>
                            <div class="form-group">
                                <label>Loan</label>
                                <div>{{ $ownerAccount->loan }}/{{ $ownerAccount->max_loan }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-6 right">
                        @if(isset($company) && $companyAccount)
                            <h4>{{ $company->name }} Account</h4>
                            <div class="form-group">
                                <label>Balance</label>
                                <div>{{ $companyAccount->balance }}</div>
                            </div>
                            <div class="form-group">
                                <label>Loan</label>
                                <div>{{ $companyAccount->loan }}/{{ $companyAccount->max_loan }}</div>
                            </div>
                        @endif
                    </div>
                        <table class="table task-table">
                            <thead>
                                <th>Time</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at }}</td>
                                        <td>{{ $log->data }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
