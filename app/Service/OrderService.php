<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{

    static function storeOrder(
        string $transaction_id,
        int $buyer_id,
        string $status,
        float $total_amount,
        float $paid_amount,
        string $currency,
        string $payment_method,
    ) {
        try {
            $order = new Order();
            $order->invoice_id = uniqid();
            $order->buyer_id = $buyer_id;
            $order->status = $status;
            $order->total_amount = $total_amount;
            $order->paid_amount = $paid_amount;
            $order->currency = $currency;
            $order->payment_method = $payment_method;
            $order->transaction_id = $transaction_id;
            $order->save();

            /** store order items */
            $cart = Cart::where('user_id', $buyer_id);
            $cartItems = $cart->get();
            foreach ($cartItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->price = $item->course->discount > 0 ? $item->course->discount : $item->course->price;
                $orderItem->course_id = $item->course->id;
                $orderItem->commission_rate = config('settings.commission_rate');
                $orderItem->save();

                /** store enrollment */
                $enrollment = new Enrollment();
                $enrollment->user_id = $buyer_id;
                $enrollment->course_id = $item->course->id;
                $enrollment->instructor_id = $item->course->instructor_id;
                $enrollment->save();

                /** add commission to instructor wallet (legacy fallback) and immutable payout ledger */
                $instructorWallet = $item->course->instructor;
                $coursePrice = $item->course->discount > 0 ? $item->course->discount : $item->course->price;
                $commission = calculateCommission($coursePrice, config('settings.commission_rate'));
                
                $instructorWallet->wallet += $commission;
                $instructorWallet->save();

                // Phase 4: Immutable ledger entry for partner payout
                $license = \App\Models\CourseLicense::where('course_id', $item->course->id)->whereIn('status', ['active', 'legacy_active'])->first();
                if ($license) {
                    $rule = \App\Models\PartnerPayoutRule::where('content_partner_id', $license->content_partner_id)
                                ->where(function($q) use ($license) {
                                    $q->where('course_license_id', $license->id)->orWhereNull('course_license_id');
                                })->first();

                    if ($rule) {
                        $payoutAmount = $rule->rule_type === 'fixed_fee' ? $rule->amount : ($coursePrice * ($rule->amount / 100));
                        \App\Models\PartnerPayout::create([
                            'content_partner_id' => $license->content_partner_id,
                            'rule_id' => $rule->id,
                            'amount' => $payoutAmount,
                            'currency' => $order->currency,
                            'period_start' => now(),
                            'period_end' => now(),
                            'status' => 'pending',
                            'payment_reference' => 'ORDER-' . $order->id,
                        ]);
                    }
                }
            }


            /** delete cart items */
            $cart->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
