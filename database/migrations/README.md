# Kigali Drive Rentals — Database Migrations

## Layout

| Location | Purpose |
|----------|---------|
| `database/migrations/*.php` | **Active** schema for Kigali Drive Rentals |
| `database/migrations/_legacy/` | Archived Kigali Drive Rentals / hospitality migrations (reference only, not run) |

## Fresh install

```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=KigaliDriveSeeder
```

## Existing database (already ran old migrations)

1. **Back up your database.**
2. Run new migrations (creates skip if tables already exist; drops remove legacy tables):

```bash
php artisan migrate
```

3. Optional: remove archived migration names from the `migrations` table if you want a clean history (not required for the app to work).

## What is kept

- Auth: `users`, `roles`, sessions, tokens, jobs
- Site: `settings`, `abouts`, `terms`, `slides`, `blogs`, `blog_comments`
- Apartments: `properties`, `units`, amenities, pricing, availability, images
- Cars: `cars`, `carimages`, `car_rentals`
- Bookings: `hotel_bookings` (apartment reservations), `booking_comments`, `booking_stay_modifications`
- Reviews: `reviews`, `review_images`
- Legal / owners: `listing_agreement_templates`, `listing_requests`

## What was removed

Hotels, trips, tours, ticketing, left luggage, invoices, owner listing signatures, trip/car/property review tables, programs/categories/partners CMS, and other Kigali Drive Rentals-only features.
