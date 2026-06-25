<?php
// app/Http/Controllers/Frontend/CheckoutController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class CheckoutController extends Controller
{
    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:150',
            'customer_email'    => 'nullable|email',
            'customer_phone'    => 'required|digits:10',
            'address_line'      => 'required|string|max:300',
            'locality'          => 'nullable|string|max:150',
            'city'              => 'required|string|max:100',
            'state'             => 'required|string|max:100',
            'pincode'           => 'required|digits:6',
            'payment_method'    => 'required|in:razorpay,cod',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.size_id'   => 'nullable|exists:filter_values,id',
            'items.*.color_id'  => 'nullable|exists:filter_values,id',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        $payment = PaymentSetting::firstOrCreate([]);

        if ($validated['payment_method'] === 'cod' && !$payment->cod_enabled) {
            return response()->json(['success' => false, 'message' => 'COD abhi available nahi hai'], 422);
        }
        if ($validated['payment_method'] === 'razorpay' && !$payment->razorpay_enabled) {
            return response()->json(['success' => false, 'message' => 'Online payment abhi available nahi hai'], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal  = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::with('primaryImage')->findOrFail($item['product_id']);

                $variant = null;
                if (!empty($item['size_id']) || !empty($item['color_id'])) {
                    $variant = ProductVariant::where('product_id', $product->id)
                        ->when(!empty($item['size_id']),  fn($q) => $q->where('size_id',  $item['size_id']))
                        ->when(!empty($item['color_id']), fn($q) => $q->where('color_id', $item['color_id']))
                        ->lockForUpdate()
                        ->first();

                    if (!$variant || $variant->stock < $item['qty']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => $product->name . ' ka selected variant stock mein nahi hai',
                        ], 422);
                    }
                }

                $basePrice = (float) ($product->sale_price ?? $product->price);
                $price     = $basePrice + (float) ($variant->extra_price ?? 0);
                $lineTotal = $price * $item['qty'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'image_path'   => $product->primaryImage->image_path ?? null,
                    'size_id'      => $variant->size_id  ?? null,
                    'color_id'     => $variant->color_id ?? null,
                    'size_label'   => $variant?->size?->value ?? null,
                    'color_label'  => $variant?->color?->label ?? $variant?->color?->value ?? null,
                    'price'        => $price,
                    'qty'          => $item['qty'],
                    'subtotal'     => $lineTotal,
                    '_variant'     => $variant,
                ];
            }

            $shipping = $subtotal >= $payment->free_shipping_above
                ? 0
                : $payment->shipping_charge;
            $total = $subtotal + $shipping;

            $order = Order::create([
                'order_number'    => 'VRD' . strtoupper(Str::random(8)),
                'customer_name'   => $validated['customer_name'],
                'customer_email'  => $validated['customer_email'] ?? null,
                'customer_phone'  => $validated['customer_phone'],
                'address_line'    => $validated['address_line'],
                'locality'        => $validated['locality'] ?? null,
                'city'            => $validated['city'],
                'state'           => $validated['state'],
                'pincode'         => $validated['pincode'],
                'subtotal'        => $subtotal,
                'shipping_charge' => $shipping,
                'total'           => $total,
                'payment_method'  => $validated['payment_method'],
                'payment_status'  => 'pending',
                'status'          => 'pending',
            ]);

            foreach ($itemsData as $data) {
                $variant = $data['_variant'] ?? null;
                unset($data['_variant']);
                $data['order_id'] = $order->id;
                OrderItem::create($data);

                if ($variant && $validated['payment_method'] === 'cod') {
                    $variant->decrement('stock', $data['qty']);
                }
            }

            // ── COD ──
            if ($validated['payment_method'] === 'cod') {
                $order->update(['status' => 'confirmed']);
                DB::commit();

                // WhatsappService::sendOrderConfirmation($order); // baad mein

                return response()->json([
                    'success'      => true,
                    'cod'          => true,
                    'order_number' => $order->order_number,
                ]);
            }

            // ── RAZORPAY SDK ──
            $api = new Api($payment->active_key, $payment->active_secret);

            $rzpOrder = $api->order->create([
                'receipt'         => $order->order_number,
                'amount'          => (int) round($total * 100),
                'currency'        => 'INR',
                'payment_capture' => 1,
            ]);

            $order->update(['razorpay_order_id' => $rzpOrder->id]);
            DB::commit();

            return response()->json([
                'success'           => true,
                'cod'               => false,
                'order_id'          => $order->id,
                'order_number'      => $order->order_number,
                'razorpay_order_id' => $rzpOrder->id,
                'razorpay_key'      => $payment->active_key,
                'amount'            => (int) round($total * 100),
                'name'              => $validated['customer_name'],
                'email'             => $validated['customer_email'] ?? '',
                'phone'             => $validated['customer_phone'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kuch galat hua: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id'            => 'required|exists:orders,id',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $order   = Order::with('items')->findOrFail($validated['order_id']);
        $payment = PaymentSetting::firstOrCreate([]);
        $api     = new Api($payment->active_key, $payment->active_secret);

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature'  => $validated['razorpay_signature'],
            ]);

            DB::beginTransaction();

            $order->update([
                'payment_status'      => 'paid',
                'status'              => 'confirmed',
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature'  => $validated['razorpay_signature'],
            ]);

            foreach ($order->items as $item) {
                if ($item->size_id || $item->color_id) {
                    ProductVariant::where('product_id', $item->product_id)
                        ->when($item->size_id,  fn($q) => $q->where('size_id',  $item->size_id))
                        ->when($item->color_id, fn($q) => $q->where('color_id', $item->color_id))
                        ->decrement('stock', $item->qty);
                }
            }

            DB::commit();

            // WhatsappService::sendOrderConfirmation($order); // baad mein

            return response()->json(['success' => true]);

        } catch (SignatureVerificationError $e) {
            $order->update(['payment_status' => 'failed']);
            return response()->json([
                'success' => false,
                'message' => 'Payment verify nahi ho saka',
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('frontend.pages.checkout-success', compact('order'));
    }
}