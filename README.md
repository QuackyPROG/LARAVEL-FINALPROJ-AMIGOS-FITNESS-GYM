# Amigos Fitness Gym — Contributors

## Cabug, John Aim Vrezymier T. — [@QuackyPROG](https://github.com/QuackyPROG)

Set up the entire project from scratch — Laravel 12, Livewire 3, Flux UI, Supabase PostgreSQL, Vite, and Pest for testing.

**Backend & Database**
- Designed and built the full database schema: users, memberships, payments, coaches, class schedules, bookings, chat, legal documents, consents, and audit logs
- Built authentication with role-based access control (admin vs member) and a force-password-change middleware

**Member Management**
- Full member CRUD — profiles, membership status, admin panel, and member portal dashboard
- Government ID type and number validation
- Digital member card with QR code token, downloadable as PDF via dompdf

**Coach & Schedule Management**
- Coach profiles with photo upload and cropping
- Class schedule grid with enrolled count and available slots
- Session booking system

**Payments**
- PayMongo integration — GCash, Maya, card, and QR Ph checkout
- Webhook handler that updates payment status in the database on confirmation
- Revenue management module with breakdowns by payment method and date range

**Communication & Notifications**
- Transactional emails — welcome, booking confirmation, and membership expiry warnings
- Events and announcements system with email broadcast to members
- Real-time support chat between members and admin using Livewire and WebSockets (Laravel Reverb)
- Claude AI chatbot integrated into the member chat widget

**Admin Tools**
- Site content editor so admin can update homepage text and images without touching code
- Legal document editor with member consent capture and immutable audit snapshots
- Audit log that records every admin action with a tamper-evident trail
- Sales summary dashboard with aggregate stats
- CSV export for the All Members page
- Coach dashboard page for admin

**Performance & Infrastructure**
- Bundled Chart.js via npm, made Google Fonts non-blocking, removed CDN tags
- Paginated announcements, events, and coach queries; added query caching
- QR code caching, debounced AI advisor calls
- Lazy loading on all non-critical images
- SPA-style navigation using `wire:navigate` across admin and portal
- AdminDataUpdated broadcast event wired to live-refresh admin UI via Reverb

**Testing**
- Wrote Pest test suite across all 19 sprints covering member flows, payment mocking, schedule logic, consent capture, ID validation, and more

---

## Karl Ishmael Gungon — [@KarlGungon](https://github.com/KarlGungon)

Handled the full visual redesign of the admin panel across all pages.

**Layout & Components**
- Overhauled the admin layout shell — navigation, structure, and overall look
- Built a reusable stat card component used across the dashboard
- Built a custom pagination component used across all admin list pages
- Added an admin splash/loading screen component

**Page Redesigns**
Restyled every admin page from the skeleton UI that was handed off:
- Dashboard — new stat layout and data display
- Members list and member detail view
- Coaches list
- Class schedules list
- Events list — including event cover image upload (with Pest test)
- Announcements list
- Plans list
- Legal document editor
- Site content editor
- Chat inbox
- Audit log

**Search & Filter**
- Added live search to the Members, Coaches, and Schedules pages — wired to Livewire components so results filter as you type

**Styling**
- Added custom CSS for admin-specific styles
- Integrated additional frontend packages via npm

---

## Ma. Gabrielle Villamor — [@magabriellevillamor](https://github.com/magabriellevillamor)

Handled the full visual redesign of the member-facing and public-facing pages.

**Landing Page**
- Redesigned the public layout and home page from the skeleton UI
- Added real gym photos (hero image and supporting visuals)

**Member Registration**
- Rebuilt the registration form UI — multi-step layout, date picker for birthdate, plan selection cards
- Finalized the full registration page design across two passes

**Member Portal**
- Redesigned the member dashboard — layout, stats, and data display
- Redesigned the coach roster page
- Restyled the member card view and its PDF download layout
- Restyled the class schedule grid, events grid, my membership page, support page, and chat widget
- Updated the portal layout shell

**Forgot Password & Password Reset**
- Built the full forgot password flow from scratch — forgot password page, reset password page, controllers, routes, and email token handling
- Updated the change password page design

**Email Templates**
- Redesigned all five transactional email layouts: welcome, booking confirmation, membership expiry warning, announcement broadcast, and change password

**Post-Payment Flow**
- Fixed the PayMongo payment confirmation screen and welcome email after a successful registration payment — created `MembershipPaymentService`, `PaymentResultController`, and a Pest test covering the confirmation flow (the checkout and webhook pipeline was already built; this corrected what broke in the post-payment step)
