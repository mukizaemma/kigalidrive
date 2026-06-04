<?php

namespace App\Services;

use App\Mail\AdminNotification;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnquirySubmissionService
{
    public function __construct(
        protected SubmissionChannelService $channels
    ) {}

    public function buildMessage(array $data): string
    {
        $lines = [
            'Kigali Drive Rentals',
            'Type: ' . ($data['form_label'] ?? $data['form_type'] ?? 'Enquiry'),
        ];

        if (! empty($data['subject'])) {
            $lines[] = 'Subject: ' . $data['subject'];
        }

        $lines[] = '';
        $lines[] = 'Name: ' . $data['names'];
        $lines[] = 'Email: ' . $data['email'];

        if (! empty($data['phone'])) {
            $lines[] = 'Phone: ' . $data['phone'];
        }

        foreach ($data['meta_lines'] ?? [] as $line) {
            $lines[] = $line;
        }

        $lines[] = '';
        $lines[] = $data['message'];

        return implode("\n", $lines);
    }

    public function storeAndDispatch(
        Request $request,
        Setting $setting,
        string $formType,
        string $channel,
        array $payload,
        string $redirectRoute,
        array $redirectParams = []
    ): JsonResponse|RedirectResponse {
        $context = $formType === Enquiry::FORM_CONTACT ? 'contact' : 'booking';
        $this->channels->assertAnyChannelActive($setting, $context);
        $this->channels->assertChannelActive($setting, $channel, $context);

        $formLabels = Enquiry::formTypeLabels();
        $payload['form_type'] = $formType;
        $payload['form_label'] = $formLabels[$formType] ?? $formType;

        $messageText = $this->buildMessage($payload);

        $enquiry = Enquiry::create([
            'form_type' => $formType,
            'submission_channel' => $channel,
            'names' => $payload['names'],
            'email' => $payload['email'],
            'phone' => $payload['phone'] ?? null,
            'subject' => $payload['subject'] ?? null,
            'message' => $payload['message'],
            'meta' => $payload['meta'] ?? null,
            'status' => 'pending',
        ]);

        $redirectUrl = route($redirectRoute, $redirectParams);
        $successMessage = 'Thank you! Your message was received. Reference #' . $enquiry->id . ' — we will respond shortly.';

        if ($channel === 'whatsapp') {
            $externalUrl = $this->channels->whatsappUrl($setting, $messageText);
            if ($externalUrl) {
                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
            }

            throw \Illuminate\Validation\ValidationException::withMessages([
                'channel' => 'WhatsApp is not available right now. Please choose another option.',
            ]);
        }

        if ($channel === 'email') {
            $adminEmail = $this->channels->adminEmail($setting, $context);
            if ($adminEmail) {
                $subject = ($payload['form_label'] ?? 'Enquiry') . ' — Kigali Drive Rentals';
                $externalUrl = 'mailto:' . $adminEmail
                    . '?subject=' . rawurlencode($subject)
                    . '&body=' . rawurlencode($messageText);

                return $this->channels->submissionResponse($request, $redirectUrl, $successMessage, $externalUrl);
            }

            throw \Illuminate\Validation\ValidationException::withMessages([
                'channel' => 'Email is not available right now. Please choose another option.',
            ]);
        }

        if ($channel === 'form') {
            $this->notifyAdmin($setting, $payload, $messageText, $context);
        }

        return $this->channels->submissionResponse($request, $redirectUrl, $successMessage);
    }

    protected function notifyAdmin(Setting $setting, array $payload, string $body, string $context): void
    {
        $email = $this->channels->adminEmail($setting, $context);
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new AdminNotification([
                'subject' => ($payload['form_label'] ?? 'Enquiry') . ' — Kigali Drive Rentals',
                'greeting' => 'Hello,',
                'body' => $body,
                'lastline' => 'View submission stats in Admin → Enquiries.',
            ]));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
