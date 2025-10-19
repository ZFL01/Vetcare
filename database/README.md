# VetCare Database Setup - MySQL

## Setup Instructions

### 1. Create Database
1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Click "New" in the left sidebar
3. Enter database name: `vetcare_db`
4. Select collation: `utf8mb4_unicode_ci`
5. Click "Create"

### 2. Import Schema
1. Select the `vetcare_db` database from the left sidebar
2. Click "Import" tab at the top
3. Click "Choose File" and select `mysql_schema.sql`
4. Click "Go" to execute the SQL

### 3. Alternative: Run SQL Directly
You can also copy and paste the contents of `mysql_schema.sql` into the SQL tab and execute it.

## Database Configuration

The database connection is configured in `includes/database.php`:

```php
private $host = 'localhost';
private $port = '3306';
private $dbname = 'vetcare_db';
private $user = 'root';
private $password = ''; 
```

### For XAMPP Users:
- Username: `root`
- Password: (leave empty)
- Host: `localhost`
- Port: `3306`

### For MAMP Users:
- Username: `root`
- Password: `root`
- Host: `localhost`
- Port: `8889` (or check your MAMP settings)

### For Custom MySQL Setup:
Update the credentials in `includes/database.php` accordingly.

## Tables Created

1. **users** - User authentication and profiles
2. **veterinarians** - Veterinarian information
3. **veterinarian_badges** - Vet specializations/services
4. **clinics** - Veterinary clinics
5. **services** - Available services
6. **articles** - Health articles
7. **consultations** - Consultation sessions
8. **consultation_messages** - Chat messages

## Sample Data

The schema includes sample data for testing:
- 1 admin user (admin@vetcare.com / password)
- 1 veterinarian (Dr. Ahmad Santoso)
- 1 clinic (Klinik Hewan Sejahtera)
- Sample services and articles

## Testing Authentication

After setup, test the authentication at:
- Login/Register: `http://localhost/VetCare/?route=auth`
- Forgot Password: `http://localhost/VetCare/?route=auth&action=forgot`

## Troubleshooting

### Connection Issues:
1. Make sure MySQL/MariaDB is running
2. Check phpMyAdmin is accessible
3. Verify database credentials in `includes/database.php`
4. Ensure PDO MySQL extension is enabled in php.ini

### Import Issues:
1. Make sure the database was created successfully
2. Check for syntax errors in the SQL file
3. Ensure you have proper permissions

### Permission Issues:
1. Make sure your MySQL user has CREATE, INSERT, SELECT, UPDATE privileges
2. For production, create a dedicated user with limited privileges
