@extends('admin.layouts.app')
@section('title', 'Vouchers')

@section('content')
<div class="page-header">
    <h3>All Vouchers</h3>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">+ Add Voucher</a>
</div>

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Times Used</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vouchers as $voucher)
            <tr>
                <td>{{ $voucher->voucher_id }}</td>
                <td>{{ $voucher->voucherName }}</td>
                <td>{{ ucfirst($voucher->voucherType) }}</td>
                <td>{{ $voucher->voucherType === 'percentage' ? $voucher->voucherAmount . '%' : '₱' . number_format($voucher->voucherAmount, 2) }}</td>
                <td>{{ $voucher->voucherUsed }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST"
                          onsubmit="return confirm('Delete this voucher?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No vouchers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $vouchers->links() }}</div>
</div>
@endsection