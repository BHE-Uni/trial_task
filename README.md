# Project Installation Guide

This guide provides step-by-step instructions on how to set up and run the project on your local development environment.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- Git
- PHP (with the required extensions for Laravel)
- Composer
- Node.js and npm

## Installation Steps

Follow these steps to install and run the project:

### 1. Clone the Repository

First, clone the project repository from GitHub to your local machine using the following command:

```bash
git clone https://github.com/BHE-Uni/trial_task.git
```

### Navigate to the project directory:

```bash
cd trial_task
```

### Use Composer to install the PHP dependencies:

```bash
composer install
```

###  Install JavaScript Dependencies

```bash
npm install
```

### Compile Assets

```bash
npm run dev
```

### Copy .env

```bash
cp .env.example .env
```

### Generate application encryption key

```bash
php artisan key:generate
```

### Database

Give the database name in the .env then migrate the database

```bash
php artisan migrate
```

### Define the API URL

If you are using a virtual host by Laragon do this change in .env

```bash
APP_URL=https://trail_task.test
```
OR, if you served by artisan command do this change in .env

```bash
http://127.0.0.1:8000
```

### Serve the Application

```bash
php artisan serve
```

### Alternatively, if you have a virtual host set up, you can access the project at:

```bash
http://trial_task.test
```
