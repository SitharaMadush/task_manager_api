# Task Manager API

This is a Laravel based Task Management REST API.

## ✨ Tech Stack Used
- Laravel 12
- PEST (for writing tests)

## 💡 Features
- Add Task
- Update Task
- Mark as completed
- Delete Tasks
- View/ Filter tasks based on status

## 💿 Local Setup Guide

1. Clone the repository

```bash
git clone https://github.com/SitharaMadush/task_manager_api
```

cd into the project directory

```bash
cd task_manager_api
```
Open the project in your code editor

Add a create a custom local domain in the hosts file of your operating system in order to .
eg: 127.0.0.1 dev.laravel.com

Rename .env.example file as .env . 
Update your Database credentials & APP_URL in .env

The built in docker setup can be used to setup the local server environment.
Please install docker on your computer before proceeding the following steps.

Change the server_name attribute in /docker/app/Dockerfile to match the created local domain
eg: server_name dev.laravel.com;

Build and run the builtin docker environment
```bash
docker compose up -d
```

Use the folowing command to access php Docker container bash
```bash
docker compose exec app -it  bash
```

Install php dependencies
```bash
composer install
```

Generate the app key
```bash
php artisan key:generate
```

Generate a secure secret key for API Token Generation
```bash
php artisan jwt:secret
```

Run the migrations
```bash
php artisan migrate
```

Seed the Database
```bash
php artisan db:seed
```

To manage CORS for the desired front end, include your front end url into the 'allowed_origins' array.
eg: 'allowed_origins' => ['http://172.20.0.2:3000'],

Additionally, to run the tests
```php
php artisan test
```

Finally the project is up and running. You can play around with your Task APIs Now...!!!

TEST USERNAME: bob@example.com
TEST PASSWORD: 'password'


💪 Developed by ~ Sithara Madushan ~ 💪 
