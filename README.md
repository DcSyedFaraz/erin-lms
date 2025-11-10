<h1 align="center">Erin LMS</h1>

Laravel-based Learning Management System for managing courses, modules, and quizzes with role-based access control, social login, and Stripe-powered subscription plans.

## Features

- Courses: create, preview, publish/archive with categories and levels
- Modules: ordered lessons with text, PDF, images, and video uploads
- Quizzes: multiple-choice and true/false, per-question points
- Roles & permissions: Admin and Parent via Spatie Permission
- Users: CRUD, role assignment, profile management
- Subscriptions: Stripe Products/Prices, plan management (Laravel Cashier)
- Social login: OAuth via Google, Facebook, LinkedIn (Socialite)
- Frontend tooling: Vite, Tailwind CSS, Alpine.js

## Tech Stack

- PHP 8.2+, Laravel 12
- SQLite (default) or MySQL
- Node 18+, Vite, Tailwind CSS

## Quick Start

1) Install dependencies

- composer install
- npm install

2) Environment

- cp .env.example .env
- For quickest start, keep `DB_CONNECTION=sqlite` (the app supports SQLite by default). Ensure the file `database/database.sqlite` exists.
- Generate key: php artisan key:generate

3) Configure services (optional but recommended)

- Stripe: set `STRIPE_SECRET` in `.env` for plan management.
- Social login providers (optional):
  - GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI
  - FACEBOOK_CLIENT_ID, FACEBOOK_CLIENT_SECRET, FACEBOOK_REDIRECT_URI
  - LINKEDIN_CLIENT_ID, LINKEDIN_CLIENT_SECRET, LINKEDIN_REDIRECT_URI

4) Migrate and seed

- php artisan migrate --seed
- php artisan storage:link

Seeding creates:

- Roles: Admin, Parent
- Default Admin user: `admin@gmail.com` / `12345678`
- Sample categories and levels

5) Run the app

- Option A (single command, runs server, queue worker, logs, and Vite):
  - composer run dev
- Option B (manual):
  - php artisan serve
  - php artisan queue:work
  - npm run dev

Visit http://localhost:8000

## Admin Panel

- URL: /admin/dashboard (requires Admin role)
- Manage users, roles/permissions
- Create courses with categories/levels and premium pricing
- Add modules with ordered content (text, PDF, images, video)
- Build quizzes (MCQ and true/false) with points
- Reorder modules via drag-and-drop (persists order)
- Create subscription plans that sync to Stripe (Product + Price)

## Social Login

Routes provided by Socialite:

- GET /login/{provider}
- GET /login/{provider}/callback

Supported providers in this project: `google`, `facebook`, `linkedin` (configure in `.env`).

Example redirect URIs when developing locally:

- http://localhost:8000/login/google/callback
- http://localhost:8000/login/facebook/callback
- http://localhost:8000/login/linkedin/callback

New social users are auto-verified and assigned the Parent role.

## Subscriptions

- Uses Laravel Cashier tables (`subscriptions`, `subscription_items`) and a local `subscription_plans` table.
- Admin can create plans in the UI; each plan creates a Stripe Product + Price and stores the `stripe_price_id` locally.
- Set `STRIPE_SECRET` in `.env` before creating plans.

## File Storage

- Uploaded thumbnails and module assets are stored on the `public` disk.
- Ensure you have run: php artisan storage:link

## Video Library

- Subscription plans now support tier metadata (Silver, Golden, Platinum) with summary text for access, update cadence, and purpose. Assign a tier when creating/editing plans so the gating rules know which audience can view each drop.
- Admins can upload curated videos, poems, premium short films, and short clips from **Admin → Video Library**. Files are stored on the `public` disk, and you can also link to external hosts while scheduling publish dates or marking featured drops.
- Authenticated subscribers with an active plan can browse `/video-library` for a plan overview, format filters, and gated access. Higher tiers automatically inherit content from lower tiers.
- Non-subscribed users are redirected to the membership page along with guidance to upgrade if they attempt to access the library.

## Testing

- php artisan test

## Common Troubleshooting

- Queue jobs not processing: start a worker (php artisan queue:work) or use the Composer `dev` script.
- 404 on uploaded files: ensure `php artisan storage:link` was executed.
- Social login error: verify OAuth credentials and redirect URIs match your provider settings.
- Stripe errors: verify `STRIPE_SECRET` is set and the account is in test mode for local development.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

MIT
