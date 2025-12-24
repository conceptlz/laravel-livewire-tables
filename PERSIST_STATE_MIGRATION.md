# Table Persist State - MySQL Migration Guide

## Overview

The table persistence feature has been migrated from cookie-based storage to MySQL database storage. This provides better security, larger storage capacity, and automatic expiry management.

## Installation Steps

### 1. Run Migration

The migration is automatically loaded from the package. Simply run:

```bash
php artisan migrate
```

**Optional:** If you prefer to publish the migration to your application's migrations folder:
```bash
php artisan vendor:publish --tag=livewire-tables-migrations
php artisan migrate
```

This will create the `table_persist_states` table with the following columns:
- `id` - Primary key
- `table_name` - Name of the table component
- `user_id` - User ID (for authenticated users)
- `session_id` - Session ID (for guest users)
- `state_data` - JSON data containing table state
- `expires_at` - Expiration timestamp
- `created_at` & `updated_at` - Timestamps

### 2. How It Works

**Automatic Persistence:**
- Table state is automatically saved to the database when changes occur
- For authenticated users: stored by `user_id`
- For guest users: stored by `session_id`
- Default expiry: 365 days (customizable)

**Automatic Retrieval:**
- State is automatically loaded from database when component initializes
- Expired records are ignored

### 3. Cleanup Expired Records

Run the cleanup command manually:
```bash
php artisan livewire-tables:cleanup-expired-states
```

Or schedule it in `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('livewire-tables:cleanup-expired-states')->daily();
}
```

## API Methods

### Clear Specific Table State
```php
// In your Livewire component
$this->clearPersistState();
```

### Cleanup Expired States (Static Method)
```php
use Rappasoft\LaravelLivewireTables\Traits\WithSavingState;

// Returns number of deleted records
$deletedCount = WithSavingState::cleanupExpiredStates();
```

## Model Methods

### Save State
```php
use Rappasoft\LaravelLivewireTables\Models\TablePersistState;

TablePersistState::saveState(
    'my-table-name',
    ['sorts' => [], 'filters' => []],
    365 // days until expiry
);
```

### Get State
```php
$state = TablePersistState::getState('my-table-name');
// Returns array or null
```

### Clear State
```php
TablePersistState::clearState('my-table-name');
```

### Cleanup Expired
```php
$deletedCount = TablePersistState::cleanupExpired();
```

## Migration from Cookies

The code automatically uses the new database storage. No changes needed in your components. The cookie-based methods (`setPersistCookie` and `getPersistCookieData`) now use database storage internally.

**Note:** Existing cookie data will not be automatically migrated. Users will need to reconfigure their table preferences after the migration.

## Benefits

1. **No Size Limits**: Unlike cookies (4KB limit), database can store large table states
2. **Security**: Sensitive filter/sort data not exposed in cookies
3. **Automatic Expiry**: Built-in cleanup mechanism for old records
4. **User Tracking**: Supports both authenticated users and guest sessions
5. **Cross-Device**: Authenticated users can access their preferences across devices
