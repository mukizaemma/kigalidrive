<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubmissionChannelService
{
    /**
     * @return array<string, string> channel key => label
     */
    public function availableChannels(Setting $setting, string $context = 'booking'): array
    {
        $channels = [];

        if ($context === 'contact') {
            if ($this->emailEnabled($setting, $context)) {
                $channels['email'] = 'Email';
            }
            if ($this->whatsappEnabled($setting)) {
                $channels['whatsapp'] = 'WhatsApp';
            }

            return $channels;
        }

        if (in_array($context, ['car_booking', 'booking', 'listing'], true)) {
            if ($this->whatsappEnabled($setting)) {
                $channels['whatsapp'] = 'WhatsApp';
            }
            if ($this->emailEnabled($setting, $context === 'car_booking' ? 'car_booking' : 'booking')) {
                $channels['email'] = 'Email';
            }

            return $channels;
        }

        return $channels;
    }

    public function assertChannelActive(Setting $setting, string $channel, string $context = 'booking'): void
    {
        $available = $this->availableChannels($setting, $context);

        if (! isset($available[$channel])) {
            throw ValidationException::withMessages([
                'channel' => 'The selected contact method is not available. Please choose another option or call us directly.',
            ]);
        }
    }

    public function assertAnyChannelActive(Setting $setting, string $context = 'booking'): void
    {
        if ($this->availableChannels($setting, $context) === []) {
            throw ValidationException::withMessages([
                'channel' => 'Online submissions are temporarily unavailable. Please call us using the phone number on this site.',
            ]);
        }
    }

    public function isValidContactEmail(?string $email): bool
    {
        if (! filled($email)) {
            return false;
        }

        return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isValidWhatsappNumber(?string $phone): bool
    {
        if (! filled($phone)) {
            return false;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        return strlen($digits) >= 9 && strlen($digits) <= 15;
    }

    public function emailEnabled(Setting $setting, string $context = 'booking'): bool
    {
        if ($context === 'contact') {
            return $this->isValidContactEmail($setting->email);
        }

        $address = $setting->booking_email ?: $setting->email;

        return (bool) ($setting->booking_email_enabled ?? true)
            && $this->isValidContactEmail($address);
    }

    public function whatsappEnabled(Setting $setting): bool
    {
        if (! ($setting->whatsapp_enabled ?? true)) {
            return false;
        }

        return $this->isValidWhatsappNumber($setting->whatsapp ?: $setting->phone);
    }

    public function whatsappNumber(Setting $setting): ?string
    {
        if (! $this->whatsappEnabled($setting)) {
            return null;
        }

        return $setting->whatsapp ?: $setting->phone;
    }

    public function adminEmail(Setting $setting, string $context = 'booking'): ?string
    {
        if ($context === 'contact') {
            return $this->emailEnabled($setting, $context) ? $setting->email : null;
        }

        if (! $this->emailEnabled($setting, $context)) {
            return null;
        }

        return $setting->booking_email ?: $setting->email;
    }

    public function whatsappUrl(Setting $setting, string $message): ?string
    {
        $phone = $this->whatsappNumber($setting);
        if (! $phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        return $digits !== '' ? 'https://wa.me/' . $digits . '?text=' . rawurlencode($message) : null;
    }

    /**
     * @param  array<string, mixed>  $flash
     */
    public function submissionResponse(
        Request $request,
        string $redirectUrl,
        string $successMessage,
        ?string $externalUrl = null,
        array $flash = []
    ): JsonResponse|RedirectResponse {
        foreach ($flash as $key => $value) {
            session()->flash($key, $value);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'open_url' => $externalUrl,
                'redirect' => $redirectUrl,
            ]);
        }

        $redirect = redirect()->to($redirectUrl)->with('success', $successMessage);
        if ($externalUrl) {
            session()->flash('kdr_open_url', $externalUrl);
        }

        return $redirect;
    }
}
