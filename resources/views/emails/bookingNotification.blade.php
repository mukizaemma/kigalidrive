<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Booking Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0071c2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .booking-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #0071c2;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .reference-number {
            background-color: #0071c2;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Booking Received</h1>
    </div>
    
    <div class="content">
        <p>Hello Admin,</p>
        <p>A new booking has been submitted and requires your attention.</p>
        
        <div class="reference-number">
            Reference Number: {{ $booking->reference_number }}
        </div>
        
        <div class="booking-details">
            <h3>Booking Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Guest Name:</span>
                <span class="detail-value">{{ $booking->guest_name ?? optional($booking->user)->name ?? '—' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Guest Email:</span>
                <span class="detail-value">{{ $booking->guest_email ?? optional($booking->user)->email ?? '—' }}</span>
            </div>
            
            @if($booking->property)
            <div class="detail-row">
                <span class="detail-label">Property:</span>
                <span class="detail-value">{{ $booking->property->name }}</span>
            </div>
            @endif

            @if($booking->hotel)
            <div class="detail-row">
                <span class="detail-label">Hotel:</span>
                <span class="detail-value">{{ $booking->hotel->name }}</span>
            </div>
            @endif
            
            @if($booking->unit)
            <div class="detail-row">
                <span class="detail-label">Room/Unit:</span>
                <span class="detail-value">{{ $booking->unit->name }}</span>
            </div>
            @endif

            @if($booking->room)
            <div class="detail-row">
                <span class="detail-label">Room type:</span>
                <span class="detail-value">{{ $booking->room->room_type }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in)->format('F d, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out)->format('F d, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Number of Nights:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Number of Guests:</span>
                <span class="detail-value">{{ $booking->guests_count }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Booking Status:</span>
                <span class="detail-value">{{ ucfirst($booking->booking_status) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">{{ ucfirst($booking->payment_status) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Booking Date:</span>
                <span class="detail-value">{{ $booking->created_at->format('F d, Y h:i A') }}</span>
            </div>
        </div>
        
        <p>Please log in to the admin panel to review and confirm this booking.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from the Accommodation Booking System.</p>
    </div>
</body>
</html>








