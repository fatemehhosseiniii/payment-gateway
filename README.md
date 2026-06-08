# Gateway System

Payment management system with support for multi-part payments.

## Requirements

* PHP 8.3
* Laravel 13
* Composer 2.x
* MySQL

## Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Environment

Copy the example environment file:

```bash
copy .env.example .env

```
Edited Variables:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gateway
DB_USERNAME=root
DB_PASSWORD=

PAGINATE_PER_PAGE=10

SHEPACOM_DRIVER=sandbox
SHEPACOM_API_KEY=test
SHEPACOM_CALLBACK=http://localhost:8000/api/payment/verify/shepa
SANDBOX_SHEPA_CALLBACK=http://localhost:8000/api/payment/verify/shepa

```

Then update the `.env` file with your database and application settings.

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations and Seed Database

```bash
php artisan migrate --seed
```

### 5. Start Development Server
    
    Start Xampp (Apache and MySql)
```bash
php artisan serve
```

### 6. Run Scheduler

To execute scheduled tasks locally:

```bash
php artisan schedule:work
```


### 7. Run Job queue For Run Event

```bash
php artisan queue:work
```


### 8. Generate Swagger docs

You can see docs in /api/documentation

```bash
php artisan l5-swagger:generate
```

## Default Admin Account

| Field    | Value                                         |
| -------- | --------------------------------------------- |
| Email    | [admin@gateway.com](mailto:admin@gateway.com) |
| Password | password                                      |

## Project Features

* Payment management
* Multi-part payment support
* Scheduled payment processing
* Administrative dashboard
