# Profit Optimiser

## Project Description
This is a simple profitability checker implemented using Laravel 12 and Vue 3. It allows users to select from list of products, input labor, costs, overheads, and a target profit margin to generate a profitability analysis. The system calculates gross profit and margin, flags low-margin items, and uses the OpenAI API to generate suggestions for improving profitability. It also includes a PDF export feature and displays a health indicator (`green`, `amber`, `red`) based on the quote’s overall profitability.

## Features
- **Quote Form** – select products and input labor, overheads, and target margin.
- **Profit Calculation** – Computes gross profit and margin with labor/overhead.
- **Line Flags** – Highlights low-margin items automatically.
- **AI Suggestions** – Generates improvements using OpenAI(gpt-4o-mini).
- **Health Indicator** – Shows quote status (Green/Amber/Red).
- **Editable Output** – AI suggestions can be modified by the user.
- **PDF Export** – Downloadable quote summary.
- **Repository Pattern** – Follows SOLID principles in Laravel.
- **REST API** – JSON-based endpoints for quote processing.
- **Validation** – Ensures all inputs are correct and safe.
- **Tests** – Includes unit test coverage.


## Assumptions
- Labor hours Threshold is 40 hours.
- Labor hours, labor cost, overhead, and targeted margin are applied to all selected products and not individually, .
- If net margin is at least 80% of the targeted margin, `Status` is `amber`.

## System Architecture
This project stack are Laravel 12, MySQL, and vue3, making it highly performant without requiring database persistence.

## Requirements

- PHP 8.2 or higher
- Nodejs 20
- Composer
- Laravel 12

## Setup & Installation

#### Installation Steps
 Run the following in the application directory:
   ```sh
   composer install
   yarn install

```

#### Copy .env.example to .env
```sh
cp .env.example .env
```
#### Generate Laravel application key
```sh
php artisan key:generate
```
#### Run migration
```sh
php artisan migrate
```
#### Seed
```sh
php artisan migrate:fresh --seed    
```

#### Start the application server
 Run the following command:
   ```sh
   php artisan serve
```

#### Open another terminal and starts the Vite development server
 Run the following command:
   ```sh
   yarn dev
```
Your application should be running on http://127.0.0.1:8000/ depend on your port availability

#### Run test
 Unit tests have been added to cover all functionality. Run tests using:
   ```sh
   php artisan test
```
#### OpenAI Key
 Please provide your OpenAI key to use the AI suggestion service
  

### Future Improvements
- Implement Laravel Middleware Throttle for Rate Limiting.
- Implement Laravel Passport/Sanctum for Authentication.
- Implement Laravel Gate and Policy for Access Control
- Implement API Versioning using middleware in route
- and more
