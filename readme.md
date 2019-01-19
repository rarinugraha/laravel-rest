Laravel Rest API + Bearer Token + Swagger


### Installing

1. Clone this repo

2. Install dependency
   ```
   composer install
   ```

3. Setup Database

4. Setup Aplications

Rename/Copy .env.example to .env and fill the environment variable.

Generate a new key for your local application
```
php artisan key:generate
```
Setting your database in .env before migrate the table

Migrate the table to database
```
php artisan migrate
```
5. Seed your database
```
php artisan db:seed
```
6. Regenerate swagger resource
```
php artisan l5-swagger:publish
```
7. Run the project
```
php artisan serve
```
7. Create user to get bearer token
```
http://localhost:8000/register
```
