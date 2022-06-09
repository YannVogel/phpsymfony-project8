How to install the project
==========================

 - clone this project with [GitHub CLI](https://cli.github.com/):

   `gh repo clone YannVogel/phpsymfony-project8`

 
- go to the project's root:

    `cd phpsymfony-project8`


- install the project dependencies with [composer](https://getcomposer.org/):

    `composer install`


- create the database:

    `bin/console doctrine:database:create`


- update the database structure:

    `bin/console doctrine:schema:update —force`


- populate the database with test data:

    `bin/console doctrine:fixtures:load -q`


- launch the app's server:

    `symfony server:start`


- access to the homepage:

    `symfony open:local`


- you can create your own user or login with theses credentials:

| Credentials \ Role | ROLE_USER | ROLE_ADMIN |
|--------------------|-----------|------------|
| Username           | user      | root       |
| Password           | password  | root       |
