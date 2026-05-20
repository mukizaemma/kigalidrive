<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New reservation received</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #25D366; color: white; padding: 16px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #e0e0e0; }
        .detail-row { padding: 6px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: 600; color: #555; }
        .footer { text-align: center; padding: 16px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header"><h2 style="margin:0;">New reservation received</h2></div>
    <div class="content">
        <p>A guest has submitted a new reservation. Details below.</p>
        <div class="detail-row"><span class="label">Name:</span> {{ $reservation->names ?? 'N/A' }}</div>
        <div class="detail-row"><span class="label">Email:</span> {{ $reservation->email ?? 'N/A' }}</div>
        <div class="detail-row"><span class="label">Phone:</span> {{ $reservation->phone ?? 'N/A' }}</div>
        <div class="detail-row"><span class="label">Service:</span> {{ $reservation->service_type ?? 'N/A' }}</div>
        @if($reservation->tour_id)
            <div class="detail-row"><span class="label">Tour:</span> {{ optional($reservation->tour)->title ?? $reservation->tour_id }}</div>
        @endif
        @if($reservation->checkin_date)
            <div class="detail-row"><span class="label">Check-in:</span> {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('F d, Y') }}</div>
        @endif
        @if($reservation->tour_date)
            <div class="detail-row"><span class="label">Tour date:</span> {{ \Carbon\Carbon::parse($reservation->tour_date)->format('F d, Y') }}</div>
        @endif
        @if($reservation->message)
            <div class="detail-row"><span class="label">Message:</span><br>{{ $reservation->message }}</div>
        @endif
    </div>
    <div class="footer">Kigali Drive Rentals – manage reservations in the admin panel.</div>
</body>
</html>
