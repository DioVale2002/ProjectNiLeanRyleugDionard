<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVoucherRequest;
use App\Http\Requests\Admin\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $vouchersQuery = Voucher::query();

        if ($search !== '') {
            $vouchersQuery->where('voucherName', 'like', "%{$search}%");
        }

        $vouchers = $vouchersQuery->orderBy('voucherName')->paginate(10)->withQueryString();

        $allVouchers = Voucher::query();
        $totalVouchers = $allVouchers->count();
        $activeVouchers = (clone $allVouchers)->where('is_active', true)->count();

        return view('admin.vouchers.index', compact(
            'vouchers',
            'search',
            'totalVouchers',
            'activeVouchers'
        ));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(StoreVoucherRequest $request)
    {
        Voucher::create($request->validated());
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher created successfully.');
    }

    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        $voucher->update($request->validated());
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher deleted successfully.');
    }
}