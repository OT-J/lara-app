Mini CRM Setup Guide
====================

This guide offers step-by-step instructions to set up the Mini CRM application on your local machine.

Prerequisites
-------------

Before setting up the Mini CRM application, ensure you have the following prerequisites installed on your machine:

1.  Node.js: Make sure Node.js is installed on your system. You can download it from the official website: [https://nodejs.org](https://nodejs.org)
    
2.  Laravel and Composer: Install Laravel and Composer to manage the PHP dependencies of the project. You can find installation instructions at [https://laravel.com/docs](https://laravel.com/docs) and [https://getcomposer.org](https://getcomposer.org) respectively.
    
3.  Database: Ensure you have a database server (e.g., MySQL, PostgreSQL) installed and running on your machine.
    

Installation Steps
------------------

Follow these steps to set up the Mini CRM application:

1.  Navigate to the project directory:

bash

```bash
cd lara-app
```

2.  Install Node.js and PHP dependencies:

bash

```bash
npm install
composer install
```

3.  Copy the environment file and set the configuration:

bash

```bash
cp .env.example .env
```

4.  Migrate the database and seed the initial data:

bash

```bash
php artisan migrate --seed
```

(Note: This will create the necessary database tables and populate them with seed data.)

5.  Build the frontend assets:

bash

```bash
npm run dev
```

6.  Start the development server:

bash

```bash
php artisan serve
```

(Note: This will launch the application on a local development server.)

Mail Testing
------------

For testing email functionality, we recommend using MailHog, a tool that captures and displays outgoing emails in a testing environment. To use MailHog, follow these steps:

1.  Download MailHog from the following link: [https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog\_windows\_amd64.exe](https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_windows_amd64.exe)
    
2.  Once downloaded, start MailHog.

3.  Access the MailHog web interface by visiting http://localhost:8025/ in your web browser. This will allow you to view and interact with the emails sent by the application.

Accessing the Application
------------

The Mini CRM application will be running at http://127.0.0.1:8000/ on your local machine. Visit this URL in your web browser to access the application.
    
