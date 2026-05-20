<?php

namespace App\Services;

use App\Models\Setting;
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

        if ($this->emailEnabled($setting, $context)) {
            $channels['email'] = 'Email';
            $channels['form'] = 'Online form (email to admin)';
        }
        if ($this->whatsappEnabled($setting)) {
            $channels['whatsapp'] = 'WhatsApp';
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

    public function emailEnabled(Setting $setting, string $context = 'booking'): bool
    {
        if ($context === 'contact') {
            return filled($setting->email);
        }

        return (bool) ($setting->booking_email_enabled ?? true)
            && filled($setting->booking_email ?: $setting->email);
    }

    public function whatsappEnabled(Setting $setting): bool
    {
        return (bool) ($setting->whatsapp_enabled ?? true)
            && filled($setting->whatsapp ?: $setting->phone);
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
}
