
## Requirements
- PHP >= 8.2
- Node >= v20.10.1

## Usage

### Backend
``` bash
	$ cd root project
```
- Create .env file, copy content from .env.example to .env file and config your database in .env:
``` bash
    APP_DEBUG=false
    APP_URL=domain
    DB_CONNECTION=mysql
    DB_HOST=database_server_ip
    DB_PORT=3306
    DB_DATABASE=database_name
    DB_USERNAME=username
    DB_PASSWORD=password
```
- Run

``` bash
	$ composer install
	$ php artisan key:generate
	$ php artisan jwt:secret
	$ php artisan migrate
	$ php artisan db:seed --class=DatabaseSeeder
	$ php artisan scribe:generate
	$ php artisan storage:link
	$ php artisan route:clear
	$ php artisan config:clear
	
```
- Local development server run

``` bash
	$ php artisan serve
```
