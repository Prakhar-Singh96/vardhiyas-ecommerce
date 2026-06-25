<?php
// app/Http/Controllers/Admin/SettingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $payment  = PaymentSetting::firstOrCreate([]);

        return view('admin.settings.edit', compact('payment'));
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'mode'                => 'required|in:test,live',
            'test_key'            => 'nullable|string',
            'test_secret'         => 'nullable|string',
            'live_key'            => 'nullable|string',
            'live_secret'         => 'nullable|string',
            'shipping_charge'     => 'nullable|numeric|min:0',
            'free_shipping_above' => 'nullable|numeric|min:0',
        ]);

        $payment = PaymentSetting::firstOrCreate([]);

        $payment->update([
            'mode'                => $validated['mode'],
            'test_key'            => $request->filled('test_key')    ? $validated['test_key']    : $payment->test_key,
            'test_secret'         => $request->filled('test_secret') ? $validated['test_secret'] : $payment->test_secret,
            'live_key'            => $request->filled('live_key')    ? $validated['live_key']    : $payment->live_key,
            'live_secret'         => $request->filled('live_secret') ? $validated['live_secret'] : $payment->live_secret,
            'razorpay_enabled'    => $request->boolean('razorpay_enabled'),
            'cod_enabled'         => $request->boolean('cod_enabled'),
            'shipping_charge'     => $validated['shipping_charge'] ?? 0,
            'free_shipping_above' => $validated['free_shipping_above'] ?? 999,
        ]);

        return back()->with('success', 'Payment settings update ho gayi!');
    }
}