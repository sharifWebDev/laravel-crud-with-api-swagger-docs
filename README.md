# Laravel Student Management API

This is a Laravel-based Student Management API with full **Swagger/OpenAPI documentation** using [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger).

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/sharifWebDev/laravel-crud-with-api-swagger-docs.git
cd laravel-crud-with-api-swagger-docs
```

2. Install PHP dependencies:

```bash
composer install
```

## Environment Setup

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

2. Generate application key:

```bash
php artisan key:generate
```

3. Set database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. Add Swagger host constant in `.env`:

```env
L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000
```

---

## Database Setup

1. Run migrations:

```bash
php artisan migrate
```


2. (Optional) or import database .sql file

---

## Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

Access the app at:

```
http://127.0.0.1:8000
```

---


### Access Swagger UI

Open in browser:

```
http://127.0.0.1:8000/api/documentation
```

---


## API Endpoint screenshot:

Student List:

![alt text](image.png)
![alt text](image-1.png)

Create Student/Post
![alt text](image-2.png)

Find student/Get by id
![alt text](image-3.png)

Update Student/put
![alt text](image-4.png)

Delete student/delete
![alt text](image-5.png)


## Student List:
![alt text](image-7.png)



##  Setup Swagger:
## Swagger Documentation

This app uses **L5-Swagger** for API documentation.

### 1. Install L5-Swagger

```bash
composer require "darkaonline/l5-swagger"
```

### 2. Publish configuration

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### 3. Add global Swagger info

Create `app/Swagger/SwaggerInfo.php`:

```php
<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="My Laravel API",
 *     version="1.0.0",
 *     description="API documentation for Laravel project",
 *     @OA\Contact(email="support@example.com")
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local Laravel API Server"
 * )
 *
 * @OA\Tag(
 *     name="Students",
 *     description="Operations related to student management"
 * )
 */
class SwaggerInfo {}
```

### 4. Configure `l5-swagger.php`

In `config/l5-swagger.php`, include:

```php
'paths' => [
    'annotations' => [
        base_path('app/Http/Controllers'),
        base_path('app/Swagger'),
    ],
],
'constants' => [
    'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://127.0.0.1:8000'),
],
```

### 5. Generate Swagger documentation

```bash
php artisan l5-swagger:generate
```

### 6. Access Swagger UI

Open in browser:

```
http://127.0.0.1:8000/api/documentation
```

---

## API Endpoints

| Method | Endpoint              | Controller                | Description                      |
| ------ | --------------------- | ------------------------- | -------------------------------- |
| GET    | /api/v1/students      | StudentController@index   | Get list of students (paginated) |
| POST   | /api/v1/students      | StudentController@store   | Create a new student             |
| GET    | /api/v1/students/{id} | StudentController@find    | Get student by ID                |
| PUT    | /api/v1/students/{id} | StudentController@update  | Update student by ID             |
| DELETE | /api/v1/students/{id} | StudentController@destroy | Delete student by ID             |

---

## Notes

* Make sure all controller methods have **L5-Swagger annotations (`@OA\*`)**.
* Ensure routes match exactly the `path` in the annotations.
* Use **pagination query params** (`page`, `per_page`) for listing endpoints.

---

This README provides a **full setup** for installing the Laravel app, running it, and generating Swagger API documentation.

---

I can also create a **ready-to-use markdown section for each CRUD method** with example requests/responses for Swagger UI, so your README doubles as a mini API doc.

Do you want me to do that?
