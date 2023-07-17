## Merca Todo - Bootcamp Evertec

Merca Todo is a web application developed during the php Bootcamp, taught by [Evertec](https://www.evertecinc.com/) in the first semester of the year 2023, the project consists of the implementation of an e-commerce, integrating the payment gateway Place to Pay, using as a base the PHP framework Laravel.

### Environment:

-   PHP 8.1
-   MySQL 8.0.32
-   Composer 2.2.6
-   Node.js 19.8.1
-   npm 9.5.1

### Patterns:

-   Actionables
-   Factories
-   Singletons
-   Query Builders
-   SOLID
    -   Single responsibility principle
    -   Open/closed principle
    -   Liskov substitution principle
    -   Dependency inversion principle

### Deployment on develop environment:

-   composer install
-   npm install
-   npm run dev
-   cp .env.example .env
-   php artisan key:generate
-   php artisan storage:link
-   php artisan test
-   php artisan migrate:fresh --seed
-   php artisan serve
-   php artisan schedule:work
-   php artisan queue:work

### Deployment on production environment:

-   composer install
-   npm install
-   npm run build
-   cp .env.example .env
-   php artisan key:generate
-   php artisan storage:link
-   php artisan migrate:fresh --seed
-   configure cron tab on server to run (php artisan schedule:work)
-   configure supervisor or similar software on server to run (php artisan queue:work)

### API V1 Documentation:

-   [Documentation](https://documenter.getpostman.com/view/5904543/2s946fesr7)

### Author Information:

-   [Elber Yamid Canoles Pérez](https://github.com/ElberCanoles)
