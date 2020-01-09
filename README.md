# Laravel API Connexion LDAP (adldap2/adldap2-laravel) et gestion token OAuth2 (laravel/passport)

Tests réalisés avec le serveur LDAP mis en ligne pour tests par forumsys

Pour simplifier l'identification des modifications, les commentaires ont été préfixés avec "[LDAP / OAUTH]".

### Récupération sources et installation :

	$ git clone https://github.com/ekergreis/laravel_ldap_oauth.git
	$ composer install

Configurer le nom de votre base de données dans le fichier .env
	
	$ php artisan migrate
    $ php artisan passport:install

Récupérer clé "Password grant client" pour l'utiliser en tant que client dans les requêtes "login".

Pour informations : Commandes lancées pour générer ficher config/ldap.php et config/ldap_auth.php 

    $ php artisan vendor:publish --provider="Adldap\Laravel\AdldapServiceProvider"
    $ php artisan vendor:publish --provider="Adldap\Laravel\AdldapAuthServiceProvider"

![Connexion](https://raw.githubusercontent.com/ekergreis/laravel_ldap_oauth/master/img/demo.png)

*Auteur : Emmanuel Kergreis*
