# Davis Kenos

## Installation

Run `composer install` inside the root directory, this will create a vendor directory with the dependencies.

Run `symfony new my_project_directory --version="6.3.*" --webapp` to create project symfony.

Create a `.env.local` file inside the root directory with the following information customized to your needs:

```ini
APP_ENV=dev
APP_SECRET=app_secret

DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?charset=utf8"
```

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