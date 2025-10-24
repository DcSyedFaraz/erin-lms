<?php

namespace App\Http\Controllers\Stripe;

use App\Models\SubscriptionEvent;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController;

class CashierWebhookController extends WebhookController
{
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $this->logEvent('customer.subscription.updated', $payload);
        return parent::handleCustomerSubscriptionUpdated($payload);
    }

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        $this->logEvent('customer.subscription.deleted', $payload);
        return parent::handleCustomerSubscriptionDeleted($payload);
    }

    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $this->logEvent('invoice.payment_succeeded', $payload);
        return parent::handleInvoicePaymentSucceeded($payload);
    }

    protected function handleInvoicePaymentFailed(array $payload)
    {
        $this->logEvent('invoice.payment_failed', $payload);
        return parent::handleInvoicePaymentFailed($payload);
    }

    protected function logEvent(string $type, array $payload): void
    {
        try {
            $sub = $payload['data']['object'] ?? [];
            $stripeSubscriptionId = $sub['id'] ?? ($payload['data']['object']['subscription'] ?? null);
            $status = $sub['status'] ?? null;

            $stripePriceId = null;
            $quantity = null;
            if (isset($sub['items']['data'][0])) {
                $item = $sub['items']['data'][0];
                $stripePriceId = $item['price']['id'] ?? null;
                $quantity = $item['quantity'] ?? null;
            }

            $trialEnds = isset($sub['trial_end']) && $sub['trial_end'] ? date('Y-m-d H:i:s', $sub['trial_end']) : null;
            $endsAt = isset($sub['ended_at']) && $sub['ended_at'] ? date('Y-m-d H:i:s', $sub['ended_at']) : null;

            // Attempt to resolve user_id via metadata or lookup by stripe_id in users table
            $userId = null;
            if (isset($sub['metadata']['user_id'])) {
                $userId = (int) $sub['metadata']['user_id'];
            }

            if (!$userId && isset($payload['data']['object']['customer'])) {
                $customerId = $payload['data']['object']['customer'];
                $userId = optional(\App\Models\User::where('stripe_id', $customerId)->first())->id;
            }

            SubscriptionEvent::create([
                'user_id' => $userId ?: 0,
                'local_subscription_id' => null,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'type' => $type,
                'status' => $status,
                'stripe_price_id' => $stripePriceId,
                'quantity' => $quantity,
                'trial_ends_at' => $trialEnds,
                'ends_at' => $endsAt,
                'occurred_at' => isset($payload['created']) ? date('Y-m-d H:i:s', $payload['created']) : now(),
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            // swallow logging errors
        }
    }
}

