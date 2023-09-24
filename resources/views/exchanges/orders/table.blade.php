<div class="col-sm-12 orders">
	<table class="table task-table table-striped table-orders" id="{{ $action }}Table">
		<thead>
			<th>Qty</th>
			<th>Price</th>
			<th>Sum</th>
		</thead>
		<tbody>
			@php($sumPrice = 0)
			@php($sumQty = 0)
			@php($x = 0)
			@foreach($orders as $order)
				@php($sumPrice += $order->qty*$order->price)
				@php($sumQty += $order->qty)
				@php($x++)
				<tr onclick="return addToOrderBox(this)" data-sum-price="{{ $sumPrice }}" data-sum-qty="{{ $sumQty }}" data-qty="{{ $order->qty }}" data-price="{{ $order->price }}" data-action="{{ $action }}" id="row{{ $x }}">
					<td>{{ $order->qty }}</td>
					<td>{{ $order->price }}</td>
					<td>
						{{ $sumPrice }}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
                                    