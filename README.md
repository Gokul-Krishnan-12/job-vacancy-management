# Laravel Job Management Application

This Laravel application is designed for managing jobs, vacancies, users, and scheduled vacancies. It provides APIs and web interfaces to perform CRUD operations on various entities.

## Features

- **Jobs Management**: CRUD operations for jobs including creation, updating, deletion, and restoration.
- **Vacancies Management**: CRUD operations for vacancies associated with jobs.
- **Users Management**: Listing and managing users.
- **Scheduled Vacancies**: Managing scheduled vacancies, including creation, updating, and deletion.
- **Email Notifications**: Sending notifications to active users for newly created vacancies.

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL or SQLite database

### Installation Steps

1. **Clone the repository:**

   git clone <repository-url>
   cd laravel-job-management

2. **Install dependencies:**

   composer install

3. **Generate Application Key:**

   composer install

4. **ADD SQL and SMTP keys to env:**

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password

    MAIL_MAILER=log
    MAIL_HOST=127.0.0.1
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

5. **Run Miration:**

   php artisan migrate

6. **Run Seeder:**

   php artisan db:seed

7. **Vue files:**

   run npm i && npm run dev

8. **Starting the server:**

   php artisan serve

9. **Running the scheduler to auto delete past scheduled vacancies:**

   php artisan schedule:run




