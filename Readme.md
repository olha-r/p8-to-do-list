### Formation OpenClassrooms Developer d’application PHP/Symfony
### Project 8 : ToDo & Co - Upgrade an existing Symfony project

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/694a743702734ce8bfab71252a3016dd)](https://www.codacy.com/gh/olha-r/toDoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=olha-r/toDoList&amp;utm_campaign=Badge_Grade)    [![Codacy Badge](https://app.codacy.com/project/badge/Coverage/694a743702734ce8bfab71252a3016dd)](https://www.codacy.com/gh/olha-r/toDoList/dashboard?utm_source=github.com&utm_medium=referral&utm_content=olha-r/toDoList&utm_campaign=Badge_Coverage)
## Prerequisite in your workplace
#### Server
You need a web server with PHP7 (>=7.2.5) and MySQL.
Versions used in this project:
- **Apache** 2.4.48
- **PHP** 7.4.12
- **MySQL** 5.7.32
See more information on technical requirements in the [Symfony official documentation](https://symfony.com/doc/5.4/setup.html#technical-requirements).

#### Framework and libraries
Framework: **Symfony** ^5.4.*
Dependencies manager: **Composer** ^2.3.5

To run tests, you also need **PHPUnit**. See requirements in [PHPUnit documentation.](https://phpunit.readthedocs.io/en/9.5/installation.html#requirements)

## Installation

1. **CLONE** or **DOWNLOAD** the project
2. Open the project in your IDE/text editor. 
3. Configure environment variables
You need to configure at least these lines in **.env** file:
``DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7" ``

4. Install dependencies with ```composer install```.
5. Create the database 
If you are in a dev environment, you can create the database and fill it with fake contents with the following command:
```composer prepare-database```
> Alternatively, follow the following steps:_ 
> -  If the database does not exist, create it with the following command in the project directory:
```php bin/console doctrine:database:create```.
> -  Create database structure thanks to migrations: `
```php bin/console doctrine:migrations:migrate```
> -  Run the following command to add the fixtures to have fake contents:
```php bin/console doctrine:fixtures:load```
6. Start the server by typing this line in your terminal ```php bin/console server:start```.

## Tests
##### Configure PHP Unit
- If you want to change PHP Unit configuration, use **phpunit.xml.dist** file
- You must define DATABASE_URL for tests in **env.test** file. 
``` DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name_test?serverVersion=5.7" ```
- Create the test database and fill it with fake contents with the following command:
```composer prepare-test```
##### Run the tests
To run all tests, use the following command:``make tests``

>You can also use "make help" to see what "make" command are available.

## Contribution

See [Contributing file](CONTRIBUTING.md).
