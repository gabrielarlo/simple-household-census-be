## Simple Household Census

### Requirements
1. For Windows:
    - Xampp with Php version 8.1

2. For Mac:
   - Php version 8.1

### Installation
- clone this repo
- run `cd ./simple-household-census-be`
- run `composer install`
- copy the `.env.example`file as `.env`
- run `php artisan migrate:fresh --seed`
- run `php artisan serve` to serve this backend
- Then run the frontend side of the app
