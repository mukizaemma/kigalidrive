<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['subject'] ?? 'Car booking' }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
        .card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #0a1d37; color: #fff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 24px; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #e8f0fe; color: #0a1d37; margin-bottom: 12px; }
        .details { background: #f8f9fa; padding: 16px; border-radius: 8px; border-left: 4px solid #c9a227; white-space: pre-wrap; font-size: 14px; }
        .footer { padding: 16px 24px 24px; color: #777; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Kigali Drive Rentals</h1>
        </div>
        <div class="content">
            @if(!empty($details['status_label']))
                <div class="status">{{ $details['status_label'] }}</div>
            @endif
            <p>{{ $details['greeting'] ?? 'Hello,' }}</p>
            <p>{{ $details['intro'] ?? '' }}</p>
            <div class="details">{{ $details['body'] ?? '' }}</div>
            <p style="margin-top: 16px;">{{ $details['lastline'] ?? 'If you have questions, reply to this email or contact us on WhatsApp.' }}</p>
        </div>
        <div class="footer">
            Thank you for choosing Kigali Drive Rentals.
        </div>
    </div>
</body>
</html>
