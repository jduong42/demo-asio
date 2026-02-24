# ASIO MVP - Space Booking Service

A LAMP stack application for booking spaces (auditoriums, conference rooms, catering, and free spaces).

## Tech Stack

- **L**inux (via Docker)
- **A**pache (web server, PHP 8.2)
- **M**ySQL 8.0 (database)
- **P**HP (backend logic)
- Vanilla CSS + JavaScript (frontend)

## Project Structure

```
asio_mvp/
├── src/                    # PHP application logic
│   ├── controllers/        # Request handlers & business logic (Phase 3)
│   ├── models/             # Database models (Phase 3)
│   ├── views/              # HTML templates (Phase 3)
│   └── index.php           # Main entry point
├── public/                 # Public assets served by Apache
│   ├── css/
│   │   └── style.css       # Full stylesheet (Flexbox layout, CSS Grid)
│   ├── js/
│   │   └── main.js         # Search + keyboard accessibility
│   └── images/             # Space photos (auditorium, conference, catering, free)
├── config/
│   └── db.php              # MySQL connection (utf8mb4)
├── sql/
│   └── init.sql            # Schema creation + Finnish seed data
├── Dockerfile              # PHP 8.2 + Apache image
├── docker-compose.yml      # 3 services: web, db, phpmyadmin
└── README.md
```

---

## Quick Start

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running

### 1. Clone / open the project

```bash
cd /path/to/asio_mvp
```

### 2. Start all containers

```bash
docker-compose up --build
```

> First run takes ~1–2 minutes. MySQL will auto-run `sql/init.sql` to create tables and seed data.

### 3. Open in browser

| Service     | URL                   |
| ----------- | --------------------- |
| Application | http://localhost:8080 |
| phpMyAdmin  | http://localhost:8081 |

### 4. Stop containers

```bash
docker-compose down
```

> To also delete all database data (full reset):
>
> ```bash
> docker-compose down -v
> ```

---

## Database Access

| Setting  | Value     |
| -------- | --------- |
| Host     | localhost |
| Port     | 3306      |
| Database | asio_db   |
| User     | asio_user |
| Password | asio_pass |

### Useful DB commands

```bash
# Open MySQL shell inside the container
docker exec -it asio_db mysql --default-character-set=utf8mb4 -u asio_user -pasio_pass asio_db

# Run a one-off query
docker exec asio_db mysql --default-character-set=utf8mb4 -u asio_user -pasio_pass asio_db -e "SELECT * FROM spaces;"
```

> **Important:** Always pass `--default-character-set=utf8mb4` when running commands that include Nordic
> characters (ä, ö, å), otherwise MySQL will double-encode them.

---

## Development Progress

### Phase 1: Project Setup ✅

- [x] Folder structure
- [x] Dockerfile (PHP 8.2 + Apache, mysqli extension)
- [x] docker-compose.yml (web, db, phpmyadmin services)
- [x] Database schema (`sql/init.sql`)
- [x] README

### Phase 2: Frontend — Home Page Layout ✅

- [x] Fixed header with gradient background
  - Buttons: 👤 Rekisteröidy, 🔑 Kirjaudu sisään, 📋 Omat varaukset
  - Search bar (🔍) with live JS filtering
- [x] Breadcrumb navigation below header
- [x] Left sidebar
  - Icon navigation: 🏠 Etusivu, › Auditoriot, › Kokoustilat, › Tietoa palvelusta
  - Compact service provider info box
- [x] Main content: 2×2 grid of space cards
  - Real images per card
  - Finnish label + arrow (→) + description layout
  - Clickable + keyboard accessible (role="button", tabindex="0")
- [x] Footer with ASIO logo at true page end
- [x] WCAG 2.2 compliance
  - Semantic HTML5, ARIA labels, sr-only class
  - Keyboard navigation (Enter / Space on cards)
  - Focus indicators, AA colour contrast ratios

### Phase 3: Backend — Space Management 🔄

- [x] Fetch spaces from DB and render dynamically on home page
- [x] utf8mb4 connection — Finnish characters display correctly
- [x] DB schema refactored to Option 3 (base + extension tables)
  - `users` — shared login fields for all user types
  - `private_profiles` — extends users for private persons
  - `company_profiles` — extends users for companies (Y-tunnus, billing etc.)
  - `bookings` — references `users.id` cleanly regardless of type
- [ ] Space model class (src/models/Space.php)
- [ ] Space controller class (src/controllers/SpaceController.php)
- [ ] Space detail page
- [ ] Backend filtering by space type

### Phase 4: User Authentication 🔄

- [x] User model (src/models/User.php)
  - `findByEmail()` — duplicate check before registration
  - `createPrivateUser()` — transaction: users + private_profiles
  - `createCompanyUser()` — transaction: users + company_profiles
- [x] Auth controller (src/controllers/AuthController.php)
  - Input validation with Finnish error messages
  - `password_hash()` with PASSWORD_BCRYPT — plain text never stored
  - Duplicate email detection
  - Returns result array — no HTML output
- [x] Registration page (src/register.php)
  - [x] Shared header from home page
  - [x] Toggle between private person / company form
  - [x] Private fields: first name, last name, email, phone, password
  - [x] Company fields: company name, Y-tunnus, contact person, billing address, email, phone, password
  - [x] Privacy notice checkbox (required)
  - [x] Submit button: Lähetä rekisteröitymislomake
  - [x] Loading indicator on submit
  - [x] Success / error alert with auto-dismiss (6s) and close button
  - [x] Form hidden on success — prevents double-submit
- [ ] Login page (Kirjaudu sisään)
- [ ] Session management

### Phase 5: Booking System ❌

- [ ] Booking model (src/models/Booking.php)
- [ ] View available time slots
- [ ] Create booking
- [ ] View own reservations (Omat varaukset)
- [ ] Cancel / modify bookings

### Phase 6: Search & Filtering ❌

- [x] Live client-side search (JS — filters by label + description)
- [ ] Server-side search endpoint
- [ ] Filter by space type
- [ ] Filter by availability / date

### Phase 7: Testing & Polish ❌

- [ ] End-to-end feature testing
- [ ] Error handling and user feedback
- [ ] UI/UX refinements
- [ ] Final documentation

---

## User Stories

### Frontend (Home Page) — all complete ✅

1. As a user, I want to navigate to the front page and see available spaces
2. As a user, I want to see navigation in the left sidebar (Home, Auditoriums, Conference Rooms, Info)
3. As a user, I want a fixed header with create user, login, reservations, and search bar
4. As a user, I want to see a service provider info box in the sidebar
5. As a user, I want to see a 2×2 grid of clickable space cards with images
6. As a user, I want a footer with a company logo that appears on scroll

### Registration Page — Phase 4 ✅

1. As a user, clicking 'Rekisteröidy' on the home page redirects me to the register page
2. As a user, the register page shows the same header as the home page
3. As a user, I can choose between registering as a private person or a company
4. As a user, the form fields change depending on my selection:
   - **Private:** first name, last name, email, phone, password
   - **Company:** company name, Y-tunnus, contact person, billing address, email, phone, password
5. As a user, I must check a privacy notice checkbox before I can submit
6. As a user, after pressing 'Lähetä rekisteröitymislomake' I see an alert confirming success or describing the error
7. As a user, while the form is submitting I see a loading indicator

### Backend (Planned)

- User authentication (registration, login, sessions)
- Booking management (create, view, cancel)
- Space availability and time slot management
- Server-side search and filtering

---

## Notes

- MySQL data is persisted in a Docker named volume (`db_data`). Run `docker-compose down -v` to fully reset.
- `sql/init.sql` only runs automatically when the volume is first created. To re-run it, destroy the volume first with `docker-compose down -v`.
- Always use `--default-character-set=utf8mb4` in `mysql` CLI commands to avoid double-encoding Nordic characters.
