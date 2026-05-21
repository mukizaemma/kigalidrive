<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Models\ListingRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ListingSubmissionService
{
    public function __construct(
        protected SubmissionChannelService $channels
    ) {}

    public function buildMessage(ListingRequest $listing): string
    {
        $channelLabel = match ($listing->submission_channel) {
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'form' => 'Online form',
            default => '—',
        };

        $lines = [
            'Kigali Drive Rentals — List Your Car',
            'Reference #: ' . $listing->id,
            '',
            'Name: ' . $listing->contact_name,
            'Phone: ' . $listing->phone,
            'Email: ' . ($listing->email ?: '—'),
            'Listing: ' . ucfirst($listing->product_type) . ' — ' . ucfirst($listing->ad_type),
            'Location: ' . $listing->location,
            'Amount: ' . ($listing->amount ? number_format((float) $listing->amount) . ' RWF' : '—'),
            'Submitted via: ' . $channelLabel,
        ];

        if ($listing->details) {
            $lines[] = '';
            $lines[] = 'Details:';
            $lines[] = $listing->details;
        }

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function storeAndDispatch(
        Request $request,
        Setting $setting,
        string $channel,
        array $payload
    ): JsonResponse|RedirectResponse {
        $context = 'booking';
        $this->channels->assertAnyChannelActive($setting, $context);
        $this->channels->assertChannelActive($setting, $channel, $context);

        $record = collect($payload)->except('vehicle_info')->all();

        $listing = ListingRequest::create(array_merge($record, [
            'submission_channel' => $channel,
            'status' => 'pending',
        ]));

        $messageText = $this->buildMessage($listing);
        $redirectUrl = route('listYourProperty');
        $successMessage = 'Your listing request was received (reference #' . $listing->id . '). Our team will contact you shortly.';

        if ($channel === 'whatsapp') {
            $externalUrl = $this->channels->whatsappUrl($setting, $messageText);
            if ($externalUrl) {
                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
            }
        }

        if ($channel === 'email') {
            $adminEmail = $this->channels->adminEmail($setting, $context);
            if ($adminEmail) {
                $subject = 'List your car #' . $listing->id . ' — Kigali Drive Rentals';
                $externalUrl = 'mailto:' . $adminEmail
                    . '?subject=' . rawurlencode($subject)
                    . '&body=' . rawurlencode($messageText);

                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
            }
        }

        if ($channel === 'form') {
            $this->notifyAdmin($setting, $listing, $messageText, $context);
        }

        return $this->channels->submissionResponse($request, $redirectUrl, $successMessage);
    }

    protected function notifyAdmin(Setting $setting, ListingRequest $listing, string $body, string $context): void
    {
        $email = $this->channels->adminEmail($setting, $context);
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new AdminNotification([
                'subject' => 'New listing request #' . $listing->id . ' — Kigali Drive Rentals',
                'greeting' => 'Hello,',
                'body' => $body,
                'lastline' => 'Review in Admin → Listing Requests.',
            ]));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
