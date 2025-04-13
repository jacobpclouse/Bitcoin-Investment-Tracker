sudo apt upgrade

sudo apt update -y


laravel new bitcoin-investment-tracker
	(dont run npm install and npm run)

composer require laravel/sail --dev

php artisan sail:install


./vendor/bin/sail up 


php artisan migrate

./vendor/bin/sail down 

./vendor/bin/sail build --no-cache

./vendor/bin/sail up 







   INFO  Sail scaffolding installed successfully. You may run your Docker containers using Sail's "up" command.  

➜ ./vendor/bin/sail up

   WARN  A database service was installed. Run "artisan migrate" to prepare your database:  

➜ ./vendor/bin/sail php artisan migrate
