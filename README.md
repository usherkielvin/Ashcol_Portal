<p align="center"><strong>Ashcol Portal</strong><br/>Customer ticketing, role-based dashboards, and public landing site for Ashcol Airconditioning Corporation.</p>

---

## 1) Overview

- Monolith built with Laravel 11 + Blade + Tailwind CSS
- Public landing page (marketing + contact form)
- Auth (Breeze) with roles: Admin, Staff, Customer
- Ticketing: tickets, comments, statuses, priorities
- Dashboards: role-based widgets and lists

Live dev URLs (local/XAMPP):
- Landing: `http://localhost/ashcol_portal/public/`
- Login: `http://localhost/ashcol_portal/public/login`
- Dashboard: `http://localhost/ashcol_portal/public/dashboard`
- Tickets: `http://localhost/ashcol_portal/public/tickets`

Default accounts (after seeding):
- Admin: `admin@example.com` / `password`
- Staff: `staff@example.com` / `password`
- Customer: `customer@example.com` / `password`

---

## 2) Quickstart

Requirements: PHP 8.2+, Composer, Node.js LTS, MySQL (XAMPP/Laragon)

```
composer install
npm install
cp .env.example .env
php artisan key:generate

# configure DB in .env (ashcol_portal)
php artisan migrate --seed

# run
php artisan serve
npm run dev
```

If using XAMPP without `artisan serve`, access via `/public/` as shown above.

---

## 3) Architecture

- Models: `User(role)`, `Ticket`, `TicketStatus`, `TicketComment`
- Controllers: `TicketController`, `TicketCommentController`, `DashboardController`, Breeze auth controllers
- Policies: `TicketPolicy` (view/create/update/delete)
- Middleware alias: `role` → `CheckRole` (admin/staff/customer)
- Views: `resources/views` (tickets, dashboards, landing)
- Landing assets: `public/ashcol/styles.css`, `public/ashcol/script.js`, images in `public/ashcol/`

---

## 4) Data Model (current)

- users: `id, name, email, password, role`
- ticket_statuses: `id, name, color, is_default`
- tickets: `id, title, description, customer_id, assigned_staff_id, status_id, priority`
- ticket_comments: `id, ticket_id, user_id, comment`

Priorities: `low | medium | high | urgent`

---

## 5) Workloads / Employees / Branches (to be implemented)

The next domain features are planned as separate modules:

### A) Workloads (Scheduling & Assignment)
- Entities: `workloads(id, ticket_id, staff_id, start_at, end_at, status)`
- Features:
  - Assign/unassign tickets to staff
  - Staff availability calendar (basic)
  - Workload board: Today / Upcoming / Overdue

### B) Employees (HR-lite)
- Entities: `employees(id, user_id, position, skills(json), branch_id, active)`
- Features:
  - Staff roster with filters (skills/branch)
  - Link `employees.user_id` to `users.id`

### C) Branches (Geography)
- Entities: `branches(id, name, address, phone, region, active)`
- Features:
  - Branch management CRUD
  - Scope tickets and workloads by branch
  - Landing “Request Service” default branch selection

---

## 6) Roadmap / TODO

- [x] Breeze auth + roles (admin/staff/customer)
- [x] Ticketing: CRUD, comments, statuses, priorities
- [x] Role dashboards (admin/staff/customer)
- [x] Public landing page (ported design)
- [ ] Request Service → create Ticket (map landing form to `tickets.store`)
- [ ] Contact form → email + DB (leads table)
- [ ] Workloads module (assignments, schedule view)
- [ ] Employees module (roster, skills, link to users)
- [ ] Branches module (scope tickets/workloads)
- [ ] Attachments (ticket and comment uploads)
- [ ] Notifications (email on create/assign/status change)

---

## 7) Developer Commands

```
# caches
php artisan optimize:clear

# DB lifecycle
php artisan migrate
php artisan migrate:fresh --seed   # dev only

# assets
npm run dev
npm run build
```

---

## 8) Contributing

Branch naming: `feature/<name>` • Small, descriptive commits • Open PRs for review.

---

## 9) License

This project uses Laravel (MIT). See [MIT license](https://opensource.org/licenses/MIT).
