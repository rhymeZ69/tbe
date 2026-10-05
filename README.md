<p align="center">
  <a href="https://github.com/rhymeZ69/tbe" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://laravel.com/docs/12.x"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+"></a>
  <a href="https://www.mysql.com/"><img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL 8.0+"></a>
  <a href="https://github.com/rhymeZ69/tbe"><img src="https://img.shields.io/github/license/rhymeZ69/tbe" alt="License"></a>
</p>

# Three Brothers Enterprises

Three Brothers Enterprises is a **multi-product export company website and administration platform** built with Laravel 12.

The platform is designed for a Pakistan-based exporter specializing in:

* Halal meat
* Ready-made garments
* Premium rice
* Fresh vegetables

The website provides a modern public-facing company website together with a comprehensive administration panel for managing products, content, export markets, enquiries, certifications, testimonials, banners, media, and website settings.

---

## About the Project

The system combines a responsive public website with a database-driven CMS and administration dashboard.

The public website provides visitors with information about the company, products, export markets, infrastructure, certifications, and services while allowing potential customers to submit enquiries and quote requests.

The administration panel allows authorized users to manage the majority of the website's content without directly modifying the source code.

### Main Features

* Responsive public website
* Product catalogue
* Product categories
* Product varieties
* Product specifications
* Multiple product images
* Infrastructure video tours
* Certifications
* Testimonials
* Export market management
* Quote/RFP enquiries
* Contact messages
* Newsletter subscriptions
* CMS pages
* Promotional banners
* SEO settings
* Social media settings
* Site-wide settings
* User management
* Admin/editor roles
* Account management
* Image and video uploads
* Scheduled content
* Mobile-specific banner images

---

## Public Website

The public website includes the following major sections:

* Hero section
* Company statistics
* About the company
* Infrastructure
* Product categories
* Products
* Product galleries
* Manufacturing/export process
* Why choose us
* Testimonials
* Certifications
* Export markets
* Contact section
* Quote enquiry form
* Newsletter subscription
* Footer

Most major content sections are dynamically loaded from the database.

---

## Administration Panel

The administration panel is available at:

```text
/admin
```

It provides authorized users with tools to manage the website.

### Dashboard

The dashboard provides:

* Website statistics
* Recent activity
* Charts
* Top export countries
* Content overview

### Products

Administrators can manage:

* Product categories
* Products
* Product varieties
* Product specifications
* Product images

### Video Tours

Video tours support:

* YouTube
* Vimeo
* External MP4 URLs
* Uploaded video files

### Certifications

Certification management includes:

* Certification name
* Issuing organization
* Issue date
* Expiration date
* Certification logo
* Expiry warnings

### Testimonials

Administrators can manage:

* Customer name
* Country
* Testimonial content
* Star rating
* Customer avatar
* Active/inactive status

### Pages

The built-in CMS allows administrators to create pages such as:

* About Us
* Terms of Service
* Privacy Policy
* Other custom pages

### Banners

Promotional banners can be scheduled and displayed in different positions:

* Top
* Hero
* Middle
* Footer

### Quote Enquiries

The enquiry system provides:

* Quote/RFP submissions
* Product interests
* Country information
* Quantity information
* Enquiry status
* Administrative notes
* CSV export

### Contact Messages

Administrators can:

* View messages
* Mark messages as read/unread
* Perform bulk actions
* Export messages as CSV

### Newsletter

Newsletter management includes:

* Subscriber list
* Activate/deactivate subscribers
* CSV export

### Countries

Export markets can be managed through:

* Country information
* GCC classification
* Featured countries
* Active/inactive status

### Site Settings

Administrators can manage:

* Company information
* Contact information
* Social media links
* SEO metadata
* Feature toggles
* Website configuration

---

## User Roles

The application currently supports two main roles.

### Admin

Administrators have full access to the administration panel, including user management and site settings.

### Editor

Editors have access to content-management functionality but do not have full administrative privileges.

### Security Guards

The system prevents dangerous account-management operations such as:

* Deactivating your own account
* Demoting the last administrator
* Deleting the last administrator
* Deactivating the last active administrator

---

## Technology Stack

The application is built using the following technologies:

| Technology              | Version / Usage            |
| ----------------------- | -------------------------- |
| Laravel                 | 12.x                       |
| PHP                     | 8.3+                       |
| Database                | MySQL 8.0+ / MariaDB 10.6+ |
| Frontend                | Blade                      |
| CSS                     | Custom CSS                 |
| JavaScript              | Vanilla JavaScript         |
| Development Environment | Laravel Herd               |
| Storage                 | Local public uploads       |

No frontend npm build step is required for the current implementation.

---

# Getting Started

## Requirements

Before installing the application, make sure your system has:

* PHP 8.3 or higher
* Composer 2.6 or higher
* MySQL 8.0+ or MariaDB 10.6+
* Apache, Nginx, Laravel Herd, Valet, or PHP's built-in server

### Required PHP Extensions

The following PHP extensions are required:

* `mbstring`
* `openssl`
* `pdo_mysql`
* `fileinfo`
* `gd` or `imagick`

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/rhymeZ69/tbe.git
cd tbe
```

### 2. Install Composer Dependencies

```bash
composer install
```

### 3. Create the Environment File

```bash
cp .env.example .env
```

On Windows, you can copy `.env.example` manually and rename the copy to:

```text
.env
```

### 4. Generate the Application Key

```bash
php artisan key:generate
```

### 5. Create the Database

Create a MySQL database named:

```sql
CREATE DATABASE tbe
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

### 6. Configure `.env`

Update your database configuration:

```env
APP_NAME="Three Brothers Enterprises"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://tbe.test

APP_TIMEZONE=UTC
APP_DISPLAY_TIMEZONE=Asia/Karachi

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tbe
DB_USERNAME=root
DB_PASSWORD=
```

For local development, email can use the Laravel log driver:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="exports@threebrothers.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 7. Run Migrations and Seeders

For a fresh installation:

```bash
php artisan migrate:fresh --seed
```

The seeders create sample/default data including:

* Administrator account
* Countries
* Product categories
* Products
* Video tours
* Certifications
* Testimonials
* Site settings

### 8. Create the Storage Link

```bash
php artisan storage:link
```

### 9. Create Upload Directories

The application uses directories under `public/` for uploaded media.

Create:

```text
public/images/products
public/images/banners
public/images/certifications
public/images/testimonials
public/images/videos
public/images/avatars
public/images/pages
public/videos
```

### 10. Start the Application

Using Laravel's development server:

```bash
php artisan serve
```

Or use Laravel Herd.

With Herd, the project can be accessed using:

```text
http://tbe.test
```

---

# Default Administrator

After running the seeders, the default administrator account is:

```text
Email:    admin@threebrothers.com
Password: ChangeMe123!
```

**Change this password immediately after the first login.**

---

# Configuration

## Timezone

The application stores timestamps in UTC while displaying administrative dates using the configured display timezone.

```env
APP_TIMEZONE=UTC
APP_DISPLAY_TIMEZONE=Asia/Karachi
```

The display timezone is used for features such as:

* Banner scheduling
* Certification dates
* Administrative date displays
* Scheduled content

---

## File Uploads

The application uses a custom `public_uploads` filesystem disk.

Example configuration:

```php
'public_uploads' => [
    'driver' => 'local',
    'root' => public_path(),
    'url' => env('APP_URL'),
    'visibility' => 'public',
],
```

Uploaded files are stored under:

```text
public/images/products/
public/images/banners/
public/images/certifications/
public/images/testimonials/
public/images/pages/
public/images/avatars/
public/videos/
```

---

# Upload Limits

| File Type          | Maximum Size | Recommended Dimensions |
| ------------------ | -----------: | ---------------------- |
| Product Image      |         4 MB | 800 × 500              |
| Desktop Banner     |         6 MB | 1600–2560 × 700–1200   |
| Mobile Banner      |         4 MB | 600–1200 × 800–1400    |
| Certification Logo |         2 MB | Any                    |
| Testimonial Avatar |         2 MB | Square                 |
| User Avatar        |         2 MB | Square                 |
| Video              |       100 MB | MP4, WebM, MOV, OGG    |

---

# Video Tours

Video tours support multiple sources.

### YouTube

```text
https://www.youtube.com/watch?v=...
https://youtu.be/...
https://www.youtube.com/shorts/...
```

### Vimeo

```text
https://vimeo.com/123456789
```

### External Video

```text
https://cdn.example.com/video.mp4
```

### Uploaded Video

Uploaded videos are stored in:

```text
public/videos/
```

The application automatically converts supported YouTube and Vimeo URLs into embeddable URLs.

---

# Database Structure

The application uses a relational database consisting of approximately 18 tables.

## Core Tables

| Table                   | Purpose                           |
| ----------------------- | --------------------------------- |
| `users`                 | Administrator and editor accounts |
| `sessions`              | Session storage                   |
| `password_reset_tokens` | Password reset functionality      |

## Site Content

| Table           | Purpose                       |
| --------------- | ----------------------------- |
| `site_settings` | Website configuration         |
| `pages`         | CMS pages                     |
| `banners`       | Scheduled promotional banners |
| `media`         | Media library                 |

## Products

| Table                | Purpose                 |
| -------------------- | ----------------------- |
| `product_categories` | Product categories      |
| `products`           | Products                |
| `product_varieties`  | Product varieties       |
| `product_specs`      | Product specifications  |
| `product_images`     | Product image galleries |

## Trust & Markets

| Table            | Purpose                    |
| ---------------- | -------------------------- |
| `countries`      | Export destinations        |
| `certifications` | Quality certifications     |
| `testimonials`   | Customer testimonials      |
| `video_tours`    | Infrastructure video tours |

## Enquiries

| Table                    | Purpose                  |
| ------------------------ | ------------------------ |
| `quote_enquiries`        | Quote/RFP enquiries      |
| `enquiry_items`          | Enquiry product items    |
| `contact_messages`       | Website contact messages |
| `newsletter_subscribers` | Newsletter subscribers   |

---

# Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── QuoteEnquiryController.php
│   │   ├── ContactController.php
│   │   ├── NewsletterController.php
│   │   └── Admin/
│   └── ...
├── Models/
└── helpers.php

config/
├── app.php
├── filesystems.php
└── ...

public/
├── css/
│   ├── home.css
│   └── admin.css
├── js/
│   ├── home.js
│   └── admin.js
├── img/
├── images/
└── videos/

resources/
└── views/
    ├── home.blade.php
    ├── page.blade.php
    ├── partials/
    │   └── banner.blade.php
    └── admin/

routes/
└── web.php
```

---

# Email Configuration

For development, the application uses Laravel's log mailer:

```env
MAIL_MAILER=log
```

Emails can be viewed in:

```text
storage/logs/laravel.log
```

For production, configure an SMTP provider.

Example:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

MAIL_FROM_ADDRESS="exports@threebrothers.com"
MAIL_FROM_NAME="Three Brothers Enterprises"
```

**Never commit real email credentials to GitHub.**

---

# Development

## Clear Laravel Cache

When making changes to configuration, routes, views, or environment settings:

```bash
php artisan optimize:clear
```

## Optimize Laravel

```bash
php artisan optimize
php artisan view:cache
```

## Run Migrations

```bash
php artisan migrate
```

## Fresh Database

For local development when you want to recreate the database:

```bash
php artisan migrate:fresh --seed
```

> Do not use `migrate:fresh` on a production database unless you intentionally want to destroy and recreate all database tables.

---

# Deployment

Before deploying to production:

* Set `APP_ENV=production`
* Set `APP_DEBUG=false`
* Set the correct `APP_URL`
* Configure HTTPS
* Change the default administrator password
* Configure a production mail service
* Configure file permissions
* Run migrations
* Optimize Laravel
* Configure scheduled tasks
* Configure database backups
* Back up uploaded images and videos

Example production environment:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### Production Optimization

```bash
php artisan optimize
php artisan view:cache
```

### Storage Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 public/images public/videos
```

### Laravel Scheduler

Configure a cron job:

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

# Git Workflow

Check the current changes:

```bash
git status
```

Stage changes:

```bash
git add .
```

Create a commit:

```bash
git commit -m "Describe your changes"
```

Push changes:

```bash
git push origin main
```

Pull the latest changes:

```bash
git pull origin main
```

Repository:

https://github.com/rhymeZ69/tbe

---

# Security

Never commit sensitive files or credentials to the repository.

The following should remain outside Git:

```text
.env
/vendor/
/node_modules/
storage/logs/
```

Do not commit:

* Database passwords
* SMTP passwords
* API keys
* Application secrets
* Private credentials

Always use environment variables for sensitive configuration.

---

# Troubleshooting

## Admin Route Returns 404

Make sure the public dynamic `{slug}` route is located **after all admin routes** in `routes/web.php`.

Then clear the route cache:

```bash
php artisan route:clear
php artisan optimize:clear
```

---

## Uploads Are Not Saving

Check the `public_uploads` disk configuration:

```php
'root' => public_path(),
```

Then verify that the required directories exist:

```text
public/images/
public/videos/
```

On Linux production servers, check their permissions.

---

## Banner Is Not Showing

Verify:

```text
is_active = 1
```

and that:

```text
starts_at
```

is either `NULL` or already in the past.

Also verify:

```text
ends_at
```

is either `NULL` or still in the future.

The banner position must be one of:

```text
top
hero
middle
footer
```

---

## Database Does Not Exist

If Laravel reports:

```text
SQLSTATE[HY000] [1049] Unknown database 'tbe'
```

create the database:

```sql
CREATE DATABASE tbe
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Then run:

```bash
php artisan migrate
```

---

# Contributing

This is a proprietary project developed for **Three Brothers Enterprises**.

Contributions, modifications, and access to the source code are controlled by the project owner.

For development changes:

1. Create a branch for your work.
2. Make your changes.
3. Test the application.
4. Commit your changes.
5. Push the branch.
6. Submit a pull request when applicable.

---

# Code of Conduct

All developers and contributors working on this project are expected to maintain professional conduct and respect the project's codebase, users, data, and business requirements.

---

# Security Vulnerabilities

If you discover a security vulnerability, do not publicly disclose sensitive details through GitHub Issues or other public channels.

Report the vulnerability privately to the project owner so that it can be investigated and addressed appropriately.

---

# License

This project is proprietary software developed for **Three Brothers Enterprises**.

Copyright © 2026 Three Brothers Enterprises.

All rights reserved.

Unauthorized copying, distribution, modification, or commercial use is prohibited unless explicitly authorized by the project owner.

---

# Laravel

This project is powered by [Laravel](https://laravel.com/), a web application framework with expressive, elegant syntax.

For Laravel documentation, visit:

https://laravel.com/docs

For Laravel Herd documentation:

https://herd.laravel.com/docs
