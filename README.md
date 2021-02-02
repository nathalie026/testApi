# Projet "tests unitaires todolist"

### Déroulement 

* clone le repository
* `composer install`
* run son projet :
* `php -S localhost:8000 -t public` 
* Lancer les tests 
    * Lancer l'ensemble des tests : `php vendor/bin/phpunit`
    * Lancer un test spécifique (i.e ToDoListTest.php): `php vendor/bin/phpunit tests/Unit/ToDoListTest.php`
    
* TODO :
    * Tests d'intégrations
        * User
            * Controller : ajuster le isValid depuis l'entité car pas pris en compte dans le controller
            * Afficher les messages d'erreurs lors des requêtes