<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin client for the MiM SMS gateway (https://www.mimsms.com).
 *
 * All public methods are intentionally non-throwing: an SMS is a side effect
 * of a business action (e.g. creating a sale) and must never break or roll back
 * that action. Failures are logged and reported via the boolean return value.
 */
class SmsService
{
    /**
     * Whether the gateway credentials are present. Lets callers (and tests)
     * skip silently in environments where SMS isn't configured yet.
     */
    public function isConfigured(): bool
    {
        $config = config('services.mimsms');

        return !empty($config['username'])
            && !empty($config['api_key'])
            && !empty($config['sender_name']);
    }

    /**
     * Send a Bangla order-confirmation SMS for a newly created sale.
     */
    public function sendSaleNotification(?string $mobile, ?string $owner, string $invoice, float $total, float $due): bool
    {
        if (empty($mobile)) {
            return false;
        }

        $brand = config('services.mimsms.brand') ?: config('app.name');
        $owner = $owner ?: 'গ্রাহক';
        $totalText = number_format($total, 2);
        $dueText = number_format($due, 2);

        $message = "প্রিয় {$owner}, আপনার অর্ডার {$invoice} গ্রহণ করা হয়েছে। মোট: {$totalText} টাকা, বকেয়া: {$dueText} টাকা। ধন্যবাদ।";

        if (!empty($brand)) {
            $message .= " - {$brand}";
        }

        return $this->send($mobile, $message);
    }

    /**
     * Send a single SMS to one mobile number. Returns true only on a confirmed
     * gateway success. Never throws.
     */
    public function send(string $mobile, string $message): bool
    {
        $number = $this->normalizeNumber($mobile);

        if ($number === null) {
            Log::warning('MiM SMS: skipped, unrecognised mobile number format.', ['mobile' => $mobile]);
            return false;
        }

        if (!$this->isConfigured()) {
            Log::warning('MiM SMS: skipped, gateway not configured (missing username/api_key/sender_name).');
            return false;
        }

        $config = config('services.mimsms');

        try {
            $response = Http::timeout((int) ($config['timeout'] ?? 15))
                ->acceptJson()
                ->asJson()
                ->post($config['endpoint'], [
                    'UserName'        => $config['username'],
                    'Apikey'          => $config['api_key'],
                    'MobileNumber'    => $number,
                    'CampaignId'      => $config['campaign_id'] ?? 'null',
                    'SenderName'      => $config['sender_name'],
                    'TransactionType' => $config['transaction_type'] ?? 'T',
                    'Message'         => $message,
                ]);

            $body = $response->json();
            $statusCode = is_array($body) ? ($body['statusCode'] ?? null) : null;

            if ($response->successful() && (string) $statusCode === '200') {
                Log::info('MiM SMS sent.', [
                    'to'    => $number,
                    'trxnId' => is_array($body) ? ($body['trxnId'] ?? null) : null,
                ]);
                return true;
            }

            Log::error('MiM SMS: gateway returned a failure.', [
                'http_status' => $response->status(),
                'body'        => $body,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('MiM SMS: request threw an exception.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Normalise a Bangladeshi mobile number to international MSISDN form
     * (8801XXXXXXXXX) as required by the MiM gateway. Returns null when the
     * input cannot be confidently interpreted.
     */
    public function normalizeNumber(?string $mobile): ?string
    {
        if (empty($mobile)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $mobile);

        if ($digits === '') {
            return null;
        }

        // Strip an international dialling prefix like 00880...
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        // Already 8801XXXXXXXXX
        if (str_starts_with($digits, '880') && strlen($digits) === 13) {
            return $digits;
        }

        // Local 01XXXXXXXXX -> 8801XXXXXXXXX
        if (str_starts_with($digits, '01') && strlen($digits) === 11) {
            return '88' . $digits;
        }

        // Bare 1XXXXXXXXX (10 digits) -> 8801XXXXXXXXX
        if (str_starts_with($digits, '1') && strlen($digits) === 10) {
            return '880' . $digits;
        }

        return null;
    }
}
