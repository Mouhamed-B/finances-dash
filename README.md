# Finances Dashboard

A personal finance management dashboard built with Laravel and Filament.

## Requirements

-   PHP 8.1 or higher
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL database
-   GitHub account (for authentication)

## Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/yourusername/finances-dash.git
cd finances-dash
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Configure GitHub OAuth in `.env` file:

The application uses GitHub OAuth for authentication. To set this up:

1. Go to your GitHub profile Settings
2. Go to Developer Settings
3. Create a new OAuth App
4. Set the homepage URL to `http://localhost:8000`
5. Set the callback URL to `http://localhost:8000/auth/github/callback`
6. Copy the Client ID and Client Secret to your `.env` file

```
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

8. Run database migrations:

```bash
php artisan migrate
```

9. Build frontend assets:

```bash
npm run build
```

10. Start the development server:

```bash
php artisan serve
```

11. Visit `http://localhost:8000` in your browser
