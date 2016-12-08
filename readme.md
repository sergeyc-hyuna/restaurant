# Restaurant

## Deployment manual

1. Clone repository
2. Install NodeJS, Redis
3. Create and configuration **.env** file in root project folder. Example: https://gist.github.com/dudnikm-hyuna/63444d5392b52329afda47b8a385d043
    (APP_URL  --> host of your local machine)
4. Install composer dependencies:  **composer install**
5. Install npm dependencies:  **npm install**
6. Create MYSQL database and add credentials  in **.env**
    (
    ....
     DB_DATABASE=restaurant
     DB_USERNAME=root
     DB_PASSWORD=root
    ...
    )

7. Clear cache: **php artisan cache:clear**
8. Key generate: **php artisan key:generate**
9. Add config to cache: **php artisan config:cache**
10. Make migrations: **php artisan migrate**
11. Seed database: **php artisan db:seed**
12. Run socket.io server from project root folder in another console: **node socket.js**
13. Run redis server
    ( by default redis run on
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    )
14. Run project: **php artisan serve --host=xxxxxxxx**. Project will be available by address **xxxxxxxx:8000**

15. In database table **users** you can check credentials for waiter, cook, manager, trainee.

:)