# Mini CRM setup guide


```bash
cd lara-app 
npm install
composer install
php artisan migrate --seed #(Accept DB creation)
npm run dev
php artisan serve #(in another terminal)
```
Run app on http://127.0.0.1:8000/