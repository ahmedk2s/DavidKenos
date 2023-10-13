# Davis Kenos

## Installation

Create a `.env.local` file inside the root directory with the following information customized to your needs:


```ini
APP_ENV=dev
APP_SECRET=app_secret

DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?charset=utf8"
```
Run `composer install` inside the root directory, this will create a vendor directory with the dependencies.

Run `php bin/console doctrine:database:create` to create the database.

Run `php bin/console doctrine:migrations:migrate` to create the tables.

## Contributing

Please respect the following rules:

### File Names:

File naming in PascalCase for Symfony classes. For example, MaClasseController.php.

### Indentation:

Use 4 spaces for PHP code indentation.

### Variable and Function Naming:

Use camelCase notation for variable and function names in PHP. For example, $myVariable, myFunction().

### Route Naming:

Use snake_case for route names. For example, my_route_name.

### Comments:

Include descriptive comments to explain complex code, especially in critical areas or for improved visibility.