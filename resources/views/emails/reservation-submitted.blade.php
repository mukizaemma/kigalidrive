<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Submitted</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 24px; border: 1px solid #e0e0e0; border-top: none; }
        .info-box { background: #e7f3ff; border-left: 4px solid #25D366; padding: 16px; margin: 16px 0; border-radius: 8px; }
        .details { background: white; padding: 20px; margin: 16px 0; border-radius: 8px; border-left: 4px solid #25D366; }
        .detail-row { padding: 8px 0; border-bottom: 1px solid #eee; }
        .detail-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #555; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reservation Submitted</h1>
    </div>
    <div class="content">
        <p>Dear {{ $reservation->names ?? 'Guest' }},</p>
        <p>Thank you for your reservation! We have received your request successfully.</p>

        <div class="info-box">
            <strong>What happens next?</strong><br>
            Our team will review your reservation and confirm availability. We will communicate with you shortly regarding confirmation and next steps. <strong>Payment is on arrival</strong>—no advance payment is required.
        </div>

        <div class="details">
            <h3>Your Reservation Details</h3>
            @if($reservation->service_type === 'tour_booking')
                <div class="detail-row"><span class="label">Service:</span> Tour / Trip</div>
                @if($reservation->tour)
                    <div class="detail-row"><span class="label">Tour:</span> {{ $reservation->tour->title ?? 'N/A' }}</div>
                @endif
                @if($reservation->tour_date)
                    <div class="detail-row"><span class="label">Preferred Date:</span> {{ \Carbon\Carbon::parse($reservation->tour_date)->format('F d, Y') }}</div>
                @endif
                @if($reservation->tour_people || $reservation->guests)
                    <div class="detail-row"><span class="label">Number of People:</span> {{ $reservation->tour_people ?? $reservation->guests ?? 'N/A' }}</div>
                @endif
            @elseif($reservation->service_type === 'hotel_booking')
                @if($reservation->checkin_date)
                    <div class="detail-row"><span class="label">Check-in:</span> {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('F d, Y') }}</div>
                @endif
                @if($reservation->checkout_date)
                    <div class="detail-row"><span class="label">Check-out:</span> {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('F d, Y') }}</div>
                @endif
                @if($reservation->nights)
                    <div class="detail-row"><span class="label">Nights:</span> {{ $reservation->nights }}</div>
                @endif
                @if($reservation->guests)
                    <div class="detail-row"><span class="label">Guests:</span> {{ $reservation->guests }}</div>
                @endif
            @endif
            @if($reservation->message)
                <div class="detail-row" style="margin-top: 12px;"><span class="label">Your Message:</span><br><p style="margin: 8px 0 0 0;">{{ \Illuminate\Support\Str::limit($reservation->message, 200) }}</p></div>
            @endif
        </div>

        <p>If you have any questions in the meantime, please don't hesitate to contact us.</p>
        <p>Thank you for choosing us!</p>

        @php
            $setting = \App\Models\Setting::first();
            $companyEmail = $setting->email ?? config('mail.from.address');
            $companyPhone = $setting->phone ?? $setting->phone1 ?? '';
            $companyPhoneDigits = preg_replace('/\D/', '', $companyPhone);
            $whatsappUrl = $companyPhoneDigits ? 'https://wa.me/' . $companyPhoneDigits : '';
        @endphp
        <div class="info-box" style="background: #f8f9fa; border-left: 4px solid #6c757d;">
            <strong>Follow-up contacts</strong>
            <p class="mb-1 mt-2">Email: <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></p>
            @if($companyPhone)
                <p class="mb-0">Phone / WhatsApp: <a href="{{ $whatsappUrl ?: 'tel:' . $companyPhone }}" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 6px;">{{ $companyPhone }} <img src="https://static.whatsapp.net/rsrc.php/v3/yP/r/2xyb0_DNcZR.png" alt="WhatsApp" width="20" height="20" style="vertical-align: middle;"></a></p>
            @endif
        </div>
    </div>
    <div class="footer">
        <p>This is an automated confirmation. For inquiries, contact us at {{ $companyEmail }}</p>
    </div>
</body>
</html>
