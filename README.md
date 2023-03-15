## Simple Household Census

### Requirements
1. For Windows:
    - Xampp with Php version 8.1

2. For Mac:
   - Php version 8.1

### Installation
- clone this repo `git clone https://github.com/gabrielarlo/simple-household-census-be.git`
- run `cd ./simple-household-census-be`
- run `composer install`
- copy the `.env.example`file as `.env` using this command `cp .env.example .env` or `copy .env.example .env`
- run `touch database/database.sqlite`
- run `php artisan migrate:fresh --seed`
- rune `php artisan key:generate`
- run `php artisan serve` to serve this backend
- Then run the frontend side of the app
