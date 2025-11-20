# Quiz App API - Laravel Backend

A complete Laravel-based Quiz Application API with authentication, user management, quiz system, and real-time features.

## 🚀 Features

- **Authentication System** - JWT-based authentication with social login (Google/Apple)
- **User Management** - Profile management, account deletion workflow
- **Quiz System** - Categories, quizzes, questions, and scoring
- **Real-time Updates** - Live profile updates using Laravel Events
- **OTP Verification** - Email-based OTP for verification and password reset
- **API Security** - API key validation and token-based authentication
- **Comprehensive Documentation** - Swagger/OpenAPI documentation

## 📋 Prerequisites

Before installing, make sure you have:

- **PHP** 8.1 or higher
- **Composer** 2.0 or higher
- **MySQL** 5.7+ or **PostgreSQL** 9.5+
- **Node.js** (for frontend assets if needed)
- **Redis** (for caching and queues, optional but recommended)

## 🛠 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/sharifWebDev/quiz-app-api.git
cd reposetory_pattent_task
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
APP_NAME="Quiz App"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_app
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for OTP)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@quizapp.com"
MAIL_FROM_NAME="Quiz App"

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Seed Database with Sample Data

```bash
php artisan db:seed
```

This will create:
- API keys for Android and iOS
- Sample categories and quizzes
- Test questions

### 7. Install Laravel Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 8. Generate Swagger Documentation

```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

### 9. Storage Link (for file uploads)

```bash
php artisan storage:link
```

### 10. Configure Cache and Queue (Optional but Recommended)

```bash
# Clear configuration cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Run queue worker for background jobs
php artisan queue:work
```

## 🚦 Running the Application

### Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### API Documentation

Access Swagger UI at: `http://localhost:8000/api/documentation`

### Mail Testing with Mailpit (Recommended for Development)

```bash
# Install Mailpit globally
brew install mailpit

# Or run with Docker
docker run -p 1025:1025 -p 8025:8025 axllent/mailpit

# Access Mailpit at: http://localhost:8025
```

## 📱 API Usage

### Required Headers

All API requests require:

```http
API_KEY: abcd232 (for Android) or xyz2234 (for iOS)
Content-Type: application/json
```

For authenticated endpoints:
```http
Authorization: Bearer {token}
```

### Sample API Calls

#### 1. User Registration

```bash
curl -X POST http://localhost:8000/api/register \
  -H "API_KEY: abcd232" \
  -H "User-Agent": android\
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "auth_type": "email_pass",
    "firebase_id": "abcd343kejfrn"
  }'
```

#### 2. User Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "API_KEY: abcd232" \
  -H "Content-Type: application/json" \
  -H "User-Agent": android\
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### 3. Get Categories

```bash
curl -X GET http://localhost:8000/api/categories \
  -H "API_KEY: abcd232" \
  -H "User-Agent": android\
  -H "Authorization: Bearer {token}"
```

#### 4. Send OTP

```bash
curl -X POST http://localhost:8000/api/otp/send \
  -H "API_KEY: abcd232" \
  -H "Content-Type: application/json" \
  -H "User-Agent": android\
  -d '{
    "email": "john@example.com",
    "purpose": "verification"
  }'
```

## 🗄 Database Schema

### Key Tables

- **users** - User accounts and profiles
- **api_keys** - API key management
- **categories** - Quiz categories
- **quizzes** - Quiz definitions
- **questions** - Quiz questions with options
- **quiz_results** - User quiz attempts and scores
- **otps** - One-time passwords for verification
- **personal_access_tokens** - Authentication tokens

## 🔧 Configuration

### API Keys Management

Default API keys are seeded:

- **Android**: `abcd232`
- **iOS**: `xyz2234`

To add new API keys:

```bash
php artisan tinker
```

```php
App\Models\ApiKey::create([
    'key' => 'your_new_key',
    'platform' => 'android', // or 'ios', 'web'
    'is_active' => true
]);
```

### Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME="Your Quiz App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_app
DB_USERNAME=username
DB_PASSWORD=password

# Mail (Production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Caching
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🧪 Testing

### Run PHPUnit Tests

```bash
./vendor/bin/phpunit
```
  
## 📊 Monitoring and Logs

### View Logs

```bash
tail -f storage/logs/laravel.log
```

### Queue Monitoring

```bash
# Process jobs
php artisan queue:work

# Monitor failed jobs
php artisan queue:failed
php artisan queue:retry all
```

## 🔒 Security

### API Rate Limiting

The API includes rate limiting:

- **Authentication endpoints**: 5 attempts per minute
- **General endpoints**: 60 requests per minute
- **OTP endpoints**: 3 attempts per minute

### CORS Configuration

Update CORS settings in `config/cors.php`:

```php
'allowed_origins' => [
    'http://localhost:3000', // Your frontend URL
    'https://yourapp.com',
],
```

## 🚀 Deployment

### Production Setup

1. **Configure Environment**
   ```bash
   cp .env.example .env
   # Edit .env with production values
   ```

2. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

4. **Setup Queue Worker**
   ```bash
   # Using Supervisor (recommended)
   sudo nano /etc/supervisor/conf.d/laravel-worker.conf
   ```

### Using Docker (Optional)

```dockerfile
# Dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

COPY . .

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000
```

## 🆘 Troubleshooting

### Common Issues

1. **API Key Error**
   - Ensure `API_KEY` header is present
   - Check if API key exists in `api_keys` table

2. **Token Authentication Failed**
   - Verify token is included in `Authorization` header
   - Check if token has expired

3. **Database Connection**
   - Verify database credentials in `.env`
   - Ensure database server is running

4. **Mail Not Sending**
   - Check mail configuration in `.env`
   - Verify SMTP credentials

### Debug Mode

For development, enable debug mode in `.env`:

```env
APP_DEBUG=true
```

## 📞 Support

For issues and questions:

1. Check the logs: `storage/logs/laravel.log`
2. Verify API documentation: `/api/documentation`
3. Check database migrations: `php artisan migrate:status`

## 📄 License

This project is licensed under the MIT License.

--- 


