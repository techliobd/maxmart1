<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Http\Requests\Admin\CouponStoreRequest;
use App\Http\Requests\Admin\CouponUpdateRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    });
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $coupons = $query->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
        $data = $request->validated();

        // Convert usage_limit to null if empty
        if (empty($data['usage_limit'])) {
            $data['usage_limit'] = null;
        }

        // Convert min_purchase_amount to null if empty
        if (empty($data['min_purchase_amount'])) {
            $data['min_purchase_amount'] = null;
        }

        // Convert expires_at to proper format or null
        if (!empty($data['expires_at'])) {
            $data['expires_at'] = Carbon::parse($data['expires_at']);
        } else {
            $data['expires_at'] = null;
        }

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load('usedBy');

        $stats = [
            'total_uses' => $coupon->usedBy()->count(),
            'remaining_uses' => $coupon->usage_limit ? ($coupon->usage_limit - $coupon->usedBy()->count()) : 'Unlimited',
        ];

        return view('admin.coupons.show', compact('coupon', 'stats'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        $data = $request->validated();

        // Convert usage_limit to null if empty
        if (empty($data['usage_limit'])) {
            $data['usage_limit'] = null;
        }

        // Convert min_purchase_amount to null if empty
        if (empty($data['min_purchase_amount'])) {
            $data['min_purchase_amount'] = null;
        }

        // Convert expires_at to proper format or null
        if (!empty($data['expires_at'])) {
            $data['expires_at'] = Carbon::parse($data['expires_at']);
        } else {
            $data['expires_at'] = null;
        }

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Coupon status updated successfully.');
    }

    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '-COPY-' . strtoupper(substr(md5(time()), 0, 6));
        $newCoupon->usage_count = 0;
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon)->with('success', 'Coupon duplicated successfully.');
    }

    public function export(Request $request)
    {
        // Export coupons to CSV
        $coupons = Coupon::all();

        $csvData = "Code,Type,Value,Min Purchase,Usage Limit,Uses,Expires At,Status\n";

        foreach ($coupons as $coupon) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $coupon->code,
                $coupon->type,
                $coupon->value,
                $coupon->min_purchase_amount ?? '0',
                $coupon->usage_limit ?? 'Unlimited',
                $coupon->usage_count,
                $coupon->expires_at?->format('Y-m-d') ?? 'Never',
                $coupon->is_active ? 'Active' : 'Inactive'
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="coupons_' . date('Y-m-d') . '.csv"');
    }
}
