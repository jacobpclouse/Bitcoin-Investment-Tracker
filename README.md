# Bitcoin Investment Tracker GUI App
An Laravel / PhP program to track how much you have invested into Crypto vs how much growth/loss experienced. Measured against keeping money in 4% return money market and 7% S&P index fund.

### VS Code Extensions:
    Laravel
    Laravel Extra Intellisense
    PHP Create Class
    PHP Intelephense
    shadcn/vue
    Tailwind CSS Intellisense
    Vue - Official
    PHP Awesome Snippets

## Resources Used:
- Laravel Docs:
    * https://laravel.com/docs/12.x/installation
    * https://laravel.com/docs/12.x/sail
    * https://laravel.com/docs/12.x/starter-kits
- Laracast Tutorial: https://youtu.be/SqTdHCTWqks?t=778
- Setup .env file: https://www.youtube.com/watch?v=Od5MYujmCMs
- Setup New Laravel App with Sail: https://youtu.be/S_z03NUUiBk?t=165
- Learn Laravel Playlist (Laracasts): https://www.youtube.com/playlist?list=PL3VM-unCzF8hy47mt9-chowaHNjfkuEVz
- Troubleshoot Docker issues: https://youtu.be/1aDuaPhJT8E?t=711

## How to Setup and Run:
This guide explains how to set up and run the project locally using Docker, Laravel Sail, and WSL Ubuntu.

### 📥 Clone the Repository
``` 
git clone <your-repo-url>
cd <your-project-folder>
```

### ⚙️ Setup Environment File
Copy the example .env file:
```
cp .env.example .env
```

### 🛠 Install PHP Dependencies
If **vendor/** directory does not exist, install backend dependencies:
```
composer install
```
(NOTE: When creating a new app, if you want to customize sail or the Dockerfile, you should use ```php artisan sail:publish``` to copy the **vendor/** directory into a new **docker/** directory. Then you can adjust the Dockerfile and add any custom setup like cert info, etc)

### 🐳 Start Docker Containers
Bring up the app using Laravel Sail:
```
./vendor/bin/sail up -d
```

### 📦 Install Node.js Dependencies
Install frontend dependencies:
```
./vendor/bin/sail npm install
```
Build frontend assets:
```
./vendor/bin/sail npm run dev
```
(Use npm run build for production.)

<!--
### 🐳 Start Docker Containers
Bring up the app using Laravel Sail:
```
./vendor/bin/sail up -d
```
-->

### 🔑 Generate Application Key
Generate the app encryption key:
```
./vendor/bin/sail artisan key:generate
```

### 🏗️ Run Database Migrations
Apply the database schema:
```
./vendor/bin/sail artisan migrate
```

### 🌐 Access the App
Visit:
```
http://localhost
```
Your Laravel + Vue.js application should now be running!

### If you have set this up before...
You can use the following to quickly get up and running:
```
./vendor/bin/sail up -d && sail npm run dev
```