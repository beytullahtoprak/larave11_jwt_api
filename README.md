# larave11_jwt_api
Laravel 11 JWT API Authentication
#Get Start

cp .env.example .env

php artisan key:generate

php artisan jwt:secret

php artisan migrate

#Register
name,
email,
password,
password_confirmation

#Login
email,
password

#Logout
token
