# Orders API

## Technologies

-   Docker
-   MySql
-   PHP
-   Laravel
-   PHPUnit
-   Postman

## How to use

-   Clone the repository
-   Access the project directory
-   Rename `.env.example` file to `.env`
-   Start Docker and run `docker-compose up`

When starting the containers, the database will be created and the migrations will be executed, as well as the seeds to populate the database will be executed.

## How to test

-   Run `docker-compose run --rm app php artisan test`

## Endpoints

You can use the Postman collection to check and run all available endpoints. To do this, import the collection and environments from the `/Postman` directory

-   `[GET] /api/products` - List products
-   `[GET] /api/products/{id}` - Show product
-   `[POST] /api/products` - Create product
-   `[PUT] /api/products/{id}` - Update product
-   `[DELETE] /api/products/{id}` - Delete product
-   `[GET] /api/sales` - List sales
-   `[GET] /api/sales/{id}` - Show sale
-   `[POST] /api/sales` - Create sale
-   `[PUT] /api/sales/{id}/cancel` - Cancel sale
