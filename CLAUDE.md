# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is the **KIF (Kuwait International Fair) Booking System** built with Laravel 12 and Filament 4. The application uses a modern Laravel stack with Vite for asset compilation and Tailwind CSS v4 for styling.

## Development Setup

### Initial Setup

```bash
composer setup
```

This runs the complete setup: installs dependencies, creates `.env` from `.env.example`, generates app key, runs migrations, and builds frontend assets.

### Running the Development Environment

```bash
composer dev
```

This single command starts all development services concurrently:

- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Laravel Pail for log streaming (`php artisan pail`)
- Vite dev server for hot module replacement

Alternatively, run services individually:

```bash
php artisan serve        # Start web server (http://localhost:8000)
npm run dev              # Start Vite dev server for asset compilation
php artisan queue:work   # Process queued jobs
```

### Testing

```bash
composer test            # Run all tests (Pest PHP)
php artisan test         # Alternative test runner
php artisan test --filter=TestName  # Run specific test
```

The project uses **Pest PHP** (not PHPUnit) as the testing framework.

### Code Quality

```bash
./vendor/bin/pint        # Format code (Laravel Pint - PHP CS Fixer wrapper)
./vendor/bin/pint --test # Check formatting without changes
```

### Database

Default configuration uses **SQLite** (`database/database.sqlite`). The database connection is configured via `.env`:

```env
DB_CONNECTION=sqlite
```

Run migrations:

```bash
php artisan migrate              # Run pending migrations
php artisan migrate:fresh        # Drop all tables and re-run migrations
php artisan migrate:fresh --seed # Fresh migration with seeders
```

## Architecture

### Admin Panel - Filament 4

The application's primary interface is a Filament admin panel located at `/admin`. The panel is configured in [app/Providers/Filament/AdminPanelProvider.php](app/Providers/Filament/AdminPanelProvider.php).

**Panel Configuration:**

- Path: `/admin`
- Authentication: Required (built-in login page)
- Primary color: Amber
- Auto-discovery enabled for:
  - Resources: `app/Filament/Resources/`
  - Pages: `app/Filament/Pages/`
  - Widgets: `app/Filament/Widgets/`

**Creating Filament Components:**

```bash
php artisan make:filament-resource ModelName --generate  # Generate CRUD resource
php artisan make:filament-page PageName                  # Create custom page
php artisan make:filament-widget WidgetName              # Create dashboard widget
```

**Filament 4 Resource Structure:**

This project uses Filament 4's organized structure where resources are split into multiple files:

- `app/Filament/Resources/{ModelName}/` - Resource directory
  - `{ModelName}Resource.php` - Main resource class
  - `Tables/{ModelName}sTable.php` - Table configuration (columns, filters, actions)
  - `Schemas/{ModelName}Form.php` - Form schema configuration
  - `Pages/` - Custom pages (List, Create, Edit)

When customizing resources, edit the Table and Schema files rather than the main Resource class.

### Domain Model

The application manages event booth bookings with the following core models:

**Event** (`app/Models/Event.php`):

- Properties: name, starts_at, ends_at, status (draft/active/completed)
- Uses soft deletes
- Many-to-many relationship with Halls via `event_hall` pivot table
- Has many Submissions

**Hall** (`app/Models/Hall.php`):

- Properties: name
- Implements `HasMedia` for floor map SVG storage (via Spatie MediaLibrary)
- Floor maps stored in `floor_map` media collection with server-side SVG sanitization
- Many-to-many relationship with Events
- Has many Submissions

**Submission** (`app/Models/Submission.php`):

- Represents booth booking requests (no user authentication required)
- Properties: event_id, hall_id, booth_id (string), booth_name, phone_number, email (optional), company_name (optional), status (pending/approved/rejected)
- Unique constraint on [event_id, hall_id, booth_id] prevents duplicate booth submissions
- Belongs to Event and Hall

**Key Design Decisions:**

- Booths are NOT pre-registered in database; booth_id stores SVG element IDs (e.g., "A1", "B2")
- Submissions are anonymous (identified by phone number, not user accounts)
- Event-Hall scheduling conflicts are validated at the application level

### Application Structure

- **Models**: `app/Models/` - Eloquent models
- **Controllers**: `app/Http/Controllers/` - HTTP controllers (minimal usage due to Filament)
- **Routes**: `routes/web.php` - Web routes (admin handled by Filament)
- **Views**: `resources/views/` - Blade templates (public-facing event/hall browsing)
- **Frontend Assets**: `resources/css/` and `resources/js/` - Compiled via Vite
- **Migrations**: `database/migrations/` - Database schema

### Queue System

The application uses database-backed queues:

```env
QUEUE_CONNECTION=database
```

Dispatch jobs to the queue and process them with the queue worker (automatically started via `composer dev`).

### Session & Cache

Both use database drivers:

```env
SESSION_DRIVER=database
CACHE_STORE=database
```

Run migrations to create required tables (`sessions`, `cache`, `jobs`).

## Key Laravel Patterns

### Service Providers

- [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php) - Application services bootstrap
- [app/Providers/Filament/AdminPanelProvider.php](app/Providers/Filament/AdminPanelProvider.php) - Filament panel configuration

Register new service providers in `bootstrap/providers.php`.

### Artisan Commands

```bash
php artisan list                    # List all available commands
php artisan tinker                  # Interactive REPL
php artisan make:model ModelName -m # Create model with migration
php artisan make:migration name     # Create migration
php artisan make:controller Name    # Create controller
php artisan make:seeder Name        # Create seeder
php artisan db:seed                 # Run database seeders
```

## Frontend

### Public-Facing Pages

The application has a public home page (`/`) that displays event information and interactive floor maps:

- [HomeController.php](app/Http/Controllers/HomeController.php) - Fetches first event with halls
- [resources/views/pages/home.blade.php](resources/views/pages/home.blade.php) - Event browsing and booth selection UI

**SVG Floor Map Integration:**

Floor maps are uploaded as SVG files in the Filament admin and stored via Spatie MediaLibrary. The frontend:

1. Fetches the SVG from the hall's media URL dynamically
2. Parses SVG elements within a `g#Floor Map` group
3. Makes booth elements (SVG `<g>` tags with IDs) interactive with hover and click handlers
4. Shows a dialog modal for booth selection with form fields
5. Filters booths by status (all/available/booked) - availability checking to be implemented

**Important:** Booth IDs come from SVG element `id` attributes. The SVG structure must have interactive booth elements grouped under `g#Floor Map`.

### Asset Compilation

The project uses **Vite** with **Tailwind CSS v4**:

- Entry points: `resources/css/app.css` and `resources/js/app.js`
- Config: [vite.config.js](vite.config.js)
- Build for production: `npm run build`
- Development: `npm run dev` (HMR enabled)

### Tailwind CSS v4

Note this project uses **Tailwind CSS v4** (latest major version) via the Vite plugin, not the traditional PostCSS setup.

## Environment Configuration

Copy `.env.example` to `.env` and configure:

- `APP_NAME`: Application name (appears in Filament UI)
- `APP_URL`: Base URL for the application
- Database credentials (defaults to SQLite)
- Queue, cache, session drivers (defaults to database)
- Mail configuration (defaults to `log` driver)

## Important Notes

- **PHP Version**: Requires PHP 8.2 or higher
- **Public vs Admin Interface**:
  - `/` - Public home page for event browsing and booth submissions (no authentication)
  - `/admin` - Filament admin panel for managing events, halls, and submissions (authentication required)
- **SVG Security**: Floor map SVGs are sanitized server-side via custom `SanitizeSvgAdder` class to prevent XSS attacks
- **Booth System**: Booths are NOT stored in database - booth IDs reference SVG element IDs directly
- **Submission Workflow**: Submissions start as "pending" and can be bulk approved/rejected in Filament admin
- **Filament Upgrades**: Run `php artisan filament:upgrade` after updating Filament packages (automatically runs via Composer scripts)
