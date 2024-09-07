## About
* laravel 11.22.0
* php 8.2

## Notes
you should have database name `test` and user `test` with password `Password@2024` in your local machine, or you can change it in .env.

## Installation
```bash
git clone git@github.com:moathmushtaha/test.kh.git
cd test.kh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Testing
- I have added a folder named "postman" to the root of the project. This folder contains Postman collections and environment files that you can use to test the APIs.

- Also, I have already written some tasks. You can use the following command to run the tests:
```bash
php artisan test
```

