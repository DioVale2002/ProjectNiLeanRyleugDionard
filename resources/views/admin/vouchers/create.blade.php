@extends('admin.layouts.app')
@section('title', 'Add Voucher')

@section('content')
<div class="page-header">
    <h3>Add New Voucher</h3>
    <a href="{{ route('admin.vouchers.index') }}" class="btn">← Back</a>
</div>

<div class="card form-card">
    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Voucher Name</label>
            <input type="text" name="voucherName" value="{{ old('voucherName') }}" required>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="voucherType" required>
                <option value="">-- Select Type --</option>
                <option value="percentage" {{ old('voucherType') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="flat" {{ old('voucherType') === 'flat' ? 'selected' : '' }}>Flat (₱)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Amount</label>
            <input type="number" name="voucherAmount" step="0.01" min="0" value="{{ old('voucherAmount') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Voucher</button>
    </form>
</div>
@endsection