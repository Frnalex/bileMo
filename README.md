# PHP - P7 Openclassrooms - Créez un web service exposant une API

## Installation :

-   **_Étape 1 :_** Cloner le projet
-   **_Étape 2 :_** Exécuter la commande `composer install` à la racine du projet
-   **_Étape 3 :_** Créer un fichier .env.local à la racine de votre projet avec vos propres identifiants en prenant comme modèle le .env présent
-   **_Étape 4 :_** Exécuter la commande `composer prepare` pour installer la base de donnée et les fixtures.
-   **_Étape 5 :_** Générez vos clés SSL : `php bin/console lexik:jwt:generate-keypair`.
-   **_Étape 6 :_** Lancer le serveur de dev pour tester les routes : `symfony serve`.

## Documentation :

Lorsque votre serveur de dev est en route, vous pouvez accéder à la documentation sous cet url : [https://localhost:8000/api/doc](https://localhost:8000/api/doc)

## Tests :

Pour vérifier que l'api fonctionne correctement, vous pouvez lancer la commande de tests: `php bin/phpunit `

[Lien vers le code climate du projet](https://codeclimate.com/github/Frnalex/bileMo)
