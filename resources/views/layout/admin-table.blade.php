<div class="table-responsive">
    <table class="css-serial table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Creditor</th>
            <th>Amount</th>
            <th>Reference</th>
            <th>Transaction</th>
            <th>Status</th>
            <th>Transaction Date</th>
            @can('admin-can-see')
                <th>Actions</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            @if($transaction->type == 'debit')
                <tr class="danger" id="row{{ $transaction->id }}">
            @else
                <tr class="success" id="row{{ $transaction->id }}">
            @endif
                <td>&nbsp;</td>
                <td>{{ $transaction->user->fullName() }}</td>
                <td>{{ number_format($transaction->amount) }}</td>
                <td>{{ $transaction->reference }}</td>
                <td>{{ ucfirst($transaction->transaction) }}</td>
                <td>{{ ucfirst($transaction->status) }}</td>
                <td>{{ $transaction->created_at->diffForHumans() }}</td>
                @can('admin-can-see')
                    <td>
                        <button class="btn btn-primary verify" data-id="{{ $transaction->id }}" data-token="{{ csrf_token() }}" id="verify">Verify</button>
                        <button class="btn btn-danger decline" data-id="{{ $transaction->id }}" data-token="{{ csrf_token() }}" id="decline">Decline</button>
                    </td>
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>
</div>