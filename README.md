## Merca Todo - Bootcamp Evertec

Merca Todo is a web application developed during the php Bootcamp, taught by [Evertec](https://www.evertecinc.com/) in the first semester of the year 2023, the project consists of the implementation of an e-commerce, integrating the payment gateway Place to Pay, using as a base the PHP framework Laravel.

### Environment:

- PHP 8.1
- MySQL 8.0.32
- Composer 2.2.6
- Node.js 19.8.1
- npm 9.5.1

### Patterns:

- Repository
- SOLID
    * Single responsibility principle
    * Open/closed principle
    * Liskov substitution principle
    * Dependency inversion principle

### Deployment on develop environment:

- composer install
- npm install
- npm run dev
- php artisan test
- php artisan migrate:fresh --seed
- php artisan serve

### Deployment on production environment:

- composer install
- npm install
- npm run build
- php artisan migrate:fresh --seed

### Author Information:

- [Elber Yamid Canoles Pérez](https://github.com/ElberCanoles)
