## Deploying Employee Management App (Laravel, FilamentPHP, MySQL)

- Clone the repository
- Run `composer install`, `npm i && npm run dev`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Run `php artisan migrate`

## Testing the App

- Run `php artisan make:filament-user` and enter necessary credentials
- Run `php artisan serve`
- Goto URL/admin
- Login with your credentials


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
