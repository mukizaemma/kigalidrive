# Resend Email Setup for Deployment (Digital Ocean)

Add these variables to your `.env` file when deploying to Digital Ocean or any host.

## Required Environment Variables

```env
# Use Resend as the mail driver (required for Resend)
MAIL_MAILER=resend

# Resend API Key - get from https://resend.com/api-keys
RESEND_API_KEY=re_your_api_key_here

# From address - MUST be from your verified domain on Resend
# Example: notifications@Kigali Drive Rentals.com or no-reply@yourdomain.com
MAIL_FROM_ADDRESS=notifications@yourdomain.com
MAIL_FROM_NAME="Kigali Drive Rentals"
```

## Setup Steps

1. **Get your Resend API key** from [Resend Dashboard](https://resend.com/api-keys)
2. **Verify your domain** in Resend (you've already done this)
3. **Set MAIL_FROM_ADDRESS** to an address on your verified domain (e.g. `notifications@Kigali Drive Rentals.com`)
4. **Add the variables above** to your production `.env` on Digital Ocean
5. **Clear config cache** after deployment: `php artisan config:clear`

## Optional: Queue for Emails (Recommended for Production)

For better reliability, use a queue for sending emails:

```env
QUEUE_CONNECTION=database
```

Then run a queue worker: `php artisan queue:work`

Or use Redis if available:

```env
QUEUE_CONNECTION=redis
```

## Testing

After deployment, test by:
1. Creating an accommodation booking (guest receives BookingConfirmation)
2. Confirming a trip reservation in admin (guest receives ReservationConfirmed)
