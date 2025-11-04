# Application Routes Reference

## Public Routes

### Home
- `GET /` - Welcome page

---

## Authentication Routes (Public)

### Login
- `GET /login` - Login page
- `POST /login` - Process login

### Registration
- `GET /register` - Registration page
- `POST /register` - Process registration

### Password Reset
- `GET /forgot-password` - Password reset request page
- `POST /forgot-password` - Send password reset email
- `GET /reset-password/{token}` - Password reset form
- `POST /reset-password` - Process password reset

### Email Verification
- `GET /verify-email` - Email verification notice
- `GET /verify-email/{id}/{hash}` - Verify email
- `POST /email/verification-notification` - Resend verification email

### Password Confirmation
- `GET /confirm-password` - Password confirmation page
- `POST /confirm-password` - Confirm password

### Logout
- `POST /logout` - Logout user

---

## Protected Routes (Requires Authentication)

### Dashboard
- `GET /dashboard` - Role-based dashboard (Admin/Staff/Customer)

### Profile Management
- `GET /profile` - Edit profile page
- `PATCH /profile` - Update profile
- `DELETE /profile` - Delete account

---

## Ticket Routes (Requires Authentication)

### Ticket Listing & Management
- `GET /tickets` - List all tickets (filtered by role)
- `GET /tickets/create` - Create new ticket form
- `POST /tickets` - Store new ticket
- `GET /tickets/{ticket}` - View ticket details
- `GET /tickets/{ticket}/edit` - Edit ticket form (Admin/Staff only)
- `PATCH /tickets/{ticket}` - Update ticket (Admin/Staff only)
- `DELETE /tickets/{ticket}` - Delete ticket (Admin only)

### Ticket Comments
- `POST /tickets/{ticket}/comments` - Add comment to ticket
- `DELETE /ticket-comments/{ticketComment}` - Delete comment

---

## Access by Role

### Customer Access
- View own tickets only
- Create tickets
- View own ticket details
- Add comments to own tickets
- Delete own comments

### Staff Access
- View assigned tickets + unassigned tickets
- Create tickets
- Assign tickets to self
- Edit tickets (status, priority, assignment)
- View all tickets
- Add comments to any ticket
- Delete any comment

### Admin Access
- View all tickets
- Create tickets for any customer
- Assign tickets to any staff
- Edit any ticket
- Delete any ticket
- View all users
- System statistics

---

## URL Examples (XAMPP)

Since you're using XAMPP, access URLs via `/public/`:

### Public Pages
- `http://localhost/ashcol_portal/public/`
- `http://localhost/ashcol_portal/public/login`
- `http://localhost/ashcol_portal/public/register`

### Dashboard (After Login)
- `http://localhost/ashcol_portal/public/dashboard`
  - Shows Admin dashboard if user is admin
  - Shows Staff dashboard if user is staff
  - Shows Customer dashboard if user is customer

### Tickets
- `http://localhost/ashcol_portal/public/tickets`
- `http://localhost/ashcol_portal/public/tickets/create`
- `http://localhost/ashcol_portal/public/tickets/1` (view ticket #1)
- `http://localhost/ashcol_portal/public/tickets/1/edit` (edit ticket #1)

### Profile
- `http://localhost/ashcol_portal/public/profile`

---

## Test Users (from seeder)

After running migrations and seeders, you can login with:

- **Admin**: `admin@example.com` / `password`
- **Staff**: `staff@example.com` / `password`
- **Customer**: `customer@example.com` / `password`

---

## Quick Access URLs

### For Testing
1. **Home**: `http://localhost/ashcol_portal/public/`
2. **Login**: `http://localhost/ashcol_portal/public/login`
3. **Dashboard**: `http://localhost/ashcol_portal/public/dashboard`
4. **Tickets**: `http://localhost/ashcol_portal/public/tickets`
5. **Create Ticket**: `http://localhost/ashcol_portal/public/tickets/create`

---

## Route Names (for use in Blade templates)

- `route('dashboard')` - Dashboard
- `route('login')` - Login page
- `route('register')` - Registration page
- `route('tickets.index')` - Ticket list
- `route('tickets.create')` - Create ticket
- `route('tickets.show', $ticket)` - View ticket
- `route('tickets.edit', $ticket)` - Edit ticket
- `route('profile.edit')` - Edit profile

