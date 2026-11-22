# Expenses app (Laravel)

Personal expense tracker. Expenses and the currency setting are saved in a SQLite
database (`database/database.sqlite`) through Laravel.

## Requirements
- PHP 8.3 or newer, with the `pdo_sqlite` extension turned on
- Composer (https://getcomposer.org)

## First-time setup
Open a terminal in this folder and run:

```
composer install
cp .env.example .env          (on Windows: copy .env.example .env)
php artisan key:generate
php artisan migrate           (answer "yes" when asked to create the SQLite database)
```

## Run it
```
php artisan serve
```
Then open http://127.0.0.1:8000 in your browser.

## Run the tests
```
php artisan test
```

## Where things are
- `app/Models/Expense.php`: the expense model and the category list
- `app/Http/Controllers/ExpenseController.php`: add, list, edit, delete
- `app/Http/Controllers/SettingController.php`: currency setting
- `routes/web.php`: the page at `/` and the JSON API under `/api`
- `resources/views/expenses.blade.php`: the page and its JavaScript
- `database/migrations/`: the `expenses` and `settings` tables

To add a category, add it to `Expense::CATEGORIES` and to the `CATEGORIES`
list (with a colour) near the top of the script in `expenses.blade.php`.

To use MySQL instead of SQLite, change the `DB_*` lines in `.env` and run
`php artisan migrate` again.
