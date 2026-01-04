# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Qdemy is a Laravel 9 web application for online education/courses (an e-learning platform). It uses:
- **Backend**: Laravel 9 with Passport (OAuth2), Sanctum, and Socialite for authentication
- **Frontend**: Vite for asset bundling
- **Database**: MySQL with Eloquent ORM
- **Services**: Firebase (messaging/chat), BunnyCDN (storage), Google APIs

The application has multiple authentication guards (web, admin, user-api) and serves different route groups (web, admin, api, panel).

## Development Commands

### Setup
```bash
composer install          # Install PHP dependencies
npm install             # Install Node.js dependencies
php artisan key:generate # Generate APP_KEY (if needed)
php artisan migrate      # Run database migrations
php artisan db:seed      # Run database seeders
```

### Development Server
```bash
php artisan serve        # Start Laravel development server (http://localhost:8000)
npm run dev              # Start Vite dev server (hot reload for assets)
```

### Build & Optimization
```bash
npm run build            # Build production assets with Vite
php artisan cache:clear  # Clear application cache
php artisan config:cache # Cache configuration
```

### Testing
```bash
php artisan test                    # Run all tests
php artisan test --filter=TestName  # Run specific test
vendor/bin/phpunit tests/Unit       # Run unit tests only
vendor/bin/phpunit tests/Feature    # Run feature tests only
php artisan test --coverage         # Generate code coverage report
```

### Code Quality
```bash
./vendor/bin/pint          # Format code (Laravel Pint)
./vendor/bin/pint --test   # Check code style without formatting
```

### Database
```bash
php artisan migrate           # Run all pending migrations
php artisan migrate:rollback  # Rollback last migration batch
php artisan migrate:refresh   # Rollback and re-run all migrations
php artisan db:seed           # Seed the database
php artisan tinker            # Interactive PHP shell
```

## Architecture & Code Organization

### Core Layers

**Routes** (`routes/`)
- `web.php` - Public-facing web routes
- `admin.php` - Admin panel routes
- `api.php` - API endpoints (mobile/external clients)
- `panel.php` - User/instructor panel routes
- Routes use middleware for authentication (guards: `web`, `admin`, `user-api`)

**Models** (`app/Models/`)
- Eloquent models for database entities (User, Admin, Course, Category, Blog, etc.)
- Models use relationships, accessors/mutators, and soft deletes as needed
- Uses Spatie permission library for role-based access control

**Controllers** (`app/Http/Controllers/`)
- Request handling and response formatting
- Organized by domain (admin, api, web, panel subdirectories likely)
- Uses custom Form Requests for validation (`app/Http/Requests/`)

**Repositories** (`app/Repositories/`)
- Data access layer providing abstraction over models
- Examples: CartRepository, CategoryRepository, CourseRepository, SubjectRepository, MobileCartRepository
- Used to encapsulate query logic and provide domain-specific data retrieval

**Services** (`app/Services/`)
- Business logic and external integrations
- Examples: OtpService, FirebaseChatService, ContentModerationService, FollowerNotificationService, Bunny (CDN)
- Injected into controllers/repositories as dependencies

**Middleware** (`app/Http/Middleware/`)
- Custom: RoleMiddleware (role-based access control), SetLocale (i18n support)
- Standard Laravel middleware for authentication, CSRF, etc.

**Helpers** (`app/Helpers/`)
- Global helper functions (see composer.json autoload.files)
- General.php: Constants (CURRENCY, PGN, FIELD_TYPE), locale functions, translation helpers
- AppSetting.php: Application settings utilities

**Additional Layers**
- `Observers/` - Eloquent model event listeners
- `Imports/` - File import classes (Excel integration with maatwebsite/excel)
- `Exports/` - File export classes
- `Traits/` - Reusable model/class functionality
- `Exceptions/` - Custom exception handling

### Key Libraries & Integrations

**Authentication & Authorization**
- Laravel Passport (OAuth2) for API authentication via `user-api` guard
- Laravel Sanctum for token-based auth
- Spatie Laravel Permission for roles and permissions
- Multiple guards: `web` (session), `admin` (session), `user-api` (Passport)

**External Services**
- Firebase (Firestore, Cloud Messaging) via `kreait/firebase-php`
- BunnyCDN for media storage via `bunnycdn/storage`
- Google APIs (`google/apiclient`)
- OAuth via Laravel Socialite

**Data Handling**
- Maatwebsite Excel (import/export Excel files)
- Spatie Laravel Translatable (multi-language content)
- Spatie Laravel Activity Log (audit logging)
- Laravel Localization (`mcamara/laravel-localization`)

**Utilities**
- Guzzle 7.9 for HTTP requests
- Doctrine DBAL for schema alterations

### Multi-Language Support
- Application uses `mcamara/laravel-localization` and `spatie/laravel-translatable`
- Locale is set via middleware (SetLocale)
- Helper function `getLocale()` returns current locale (default: 'ar' for Arabic)
- Translation helper `translate_lang()` dynamically loads from lang files

### Database Structure
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`
- Factories in `database/factories/` for testing
- Uses Eloquent ORM with relationships between models

### Testing
- Uses PHPUnit with Laravel testing conventions
- Test suites: Unit (business logic) and Feature (HTTP requests/integration)
- Environment configured in `phpunit.xml` (testing DB, mail, cache drivers)
- Tests in `tests/Unit/` and `tests/Feature/`

## Important Patterns & Conventions

1. **Guard-specific Routes**: Check which guard each route uses - web routes use session auth, API uses Passport tokens
2. **Repository Pattern**: Data access through repositories, not directly in controllers
3. **Service Injection**: External integrations (Firebase, BunnyCDN, OTP) go through services
4. **Middleware Authorization**: Use RoleMiddleware to protect routes by permission/role
5. **Localization**: Always consider both Arabic (ar) and English (en) translations
6. **File Storage**: Use BunnyCDN service for media uploads, not local storage in production

## Configuration Files of Note

- `config/auth.php` - Guard and provider definitions
- `config/bunny.php` - BunnyCDN configuration
- `config/firebase.php` - Firebase service account and settings
- `config/permission.php` - Spatie permission caching
- `config/laravellocalization.php` - Localization settings
- `.env` - Environment variables (database, API keys, service credentials)

## Common Workflows

**Adding a New Feature**:
1. Create migration and model
2. Create repository for data access
3. Create service if external integration needed
4. Create controller with request validation
5. Add routes to appropriate route file with guard/middleware
6. Add tests for repository/service logic

**Making Database Changes**:
1. Create migration file
2. Run `php artisan migrate`
3. Update model relationships if needed
4. Update repository queries

**Working with External APIs**:
1. Create service class in `app/Services/`
2. Move configuration to `config/` file
3. Inject service into controller or repository
4. Handle responses and errors appropriately
