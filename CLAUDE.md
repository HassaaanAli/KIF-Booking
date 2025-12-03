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

### Application Structure

- **Models**: `app/Models/` - Eloquent models
- **Controllers**: `app/Http/Controllers/` - HTTP controllers (minimal usage due to Filament)
- **Routes**: `routes/web.php` - Web routes (admin handled by Filament)
- **Views**: `resources/views/` - Blade templates
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
- **Default Route**: The root route (`/`) serves a welcome view; the main application is at `/admin`
- **Authentication**: Filament handles authentication; user creation may require seeders or Filament shield
- **Filament Upgrades**: Run `php artisan filament:upgrade` after updating Filament packages (automatically runs via Composer scripts)
