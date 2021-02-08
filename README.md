# Projet "tests unitaires todolist"

### Déroulement 

* clone le repository
* `composer install`
* run son projet :
* `php -S localhost:8000 -t public` 
* Lancer les tests
    * Les tests sont lancés sur une database de tests: 
        - l'URL de la base de données de test est définie dans le fichier **.env.test.local**
        ```
        DATABASE_URL="mysql://root:@127.0.0.1:3306/db_test?serverVersion=5.7"
        ```
        - avant chaque tests, la base de donnees de test sera purgée automatiquement
        - vérifier que la base de données de test est bien créée, sinon lancer la commande 
        ```
        php bin/console doctrine:database:create --env=test
        php bin/console doctrine:schema:create --env=test

        ```

    * Lancer l'ensemble des tests : `php vendor/bin/phpunit`
    * Lancer un test spécifique (i.e ToDoListTest.php): `php vendor/bin/phpunit tests/Unit/ToDoListTest.php`, `php vendor/bin/phpunit tests/Feature/UserTestIntegration.php` 
    * Importer les tests d'intégration sur Postman depuis le fichier : `TodoList.postman_collection.json`
