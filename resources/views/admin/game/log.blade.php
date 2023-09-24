@extends('layouts.admin')

@section('main')
<style>

.log-row-shares { background: #c4e4f3;}
.log-row-accounts { background: #c0c0ff; }
.log-row-orders { background: #b794dc; }

</style>
    <h4>Game Log</h4>
    <table class="table task-table">
        <thead>
            <th>Action</th>
            <th>Data</th>
            <th>Owner ID</th>
            <th>Exchange ID</th>
            <th>Company ID</th>
            <th>Account ID</th>
        </thead>
        <tbody>
    @foreach($log as $item)
            <tr class="log-row log-row-{{ $item->action }}">
                <td>{{ $item->action }}</td>
                <td>{{ $item->data }}</td>
                <td>{{ $item->owner_id }}</td>
                <td>{{ $item->exchange_id }}</td>
                <td>{{ $item->company_id }}</td>
                <td>{{ $item->account_id }}</td>
            </tr>
    @endforeach
        </tbody>
    </table>
@endsection
