<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New message on your booking</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0071c2; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 24px; border: 1px solid #e0e0e0; border-top: none; }
        .ref { background: #e7f3ff; padding: 12px; border-radius: 6px; margin: 16px 0; font-weight: bold; }
        .comment-box { background: white; padding: 16px; border-left: 4px solid #0071c2; margin: 16px 0; border-radius: 0 8px 8px 0; }
        .meta { font-size: 12px; color: #666; margin-bottom: 8px; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New message on your booking</h1>
    </div>
    <div class="content">
        <p>Dear {{ $booking->guest_name ?? 'Guest' }},</p>
        <p>A new message has been added to your booking:</p>
        <div class="ref">Reference: {{ $booking->reference_number }}</div>
        <div class="comment-box">
            <div class="meta">
                {{ $authorLabel ?? 'Team' }} · {{ $authorName ?? 'Team' }} · {{ $createdAtFormatted ?? now()->format('F d, Y h:i A') }}
            </div>
            <p style="margin: 0; white-space: pre-wrap;">{{ $commentText ?? '' }}</p>
        </div>
        <p>You can reply or follow up by contacting us. Payment is on arrival.</p>
    </div>
    <div class="footer">
        <p>For inquiries, contact us at {{ config('mail.from.address') }}</p>
    </div>
</body>
</html>
