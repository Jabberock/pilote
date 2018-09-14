# Pilote : guide d'installation

## Manuel utilisateur

Voir la [page concernée](utilisateur.md).

## Dépendances (février 2017)

### Logiciels

- cURL
- Git
- PHP 5.6+, avec comme extensions :
    - `php-mysql` (module pour intéragir avec une base de données MariaDB/MySQL)
    - `php-curl` (module pour utiliser cURL)
    - `php-ldap` (module pour accéder à un annuaire LDAP)
    - `php-intl` (module pour l'internationalisation)

- Apache HTTP Server, avec (**suivant la version de PHP utilisée**) :
    - `libapache2-mod-php5` **(PHP 5.6)**
    - `libapache2-mod-php7.0` **(PHP 7.0)**
    - `libapache2-mod-php7.1` **(PHP 7.1)**

- MariaDB 10+ ou MySQL 5.5+
- Node.js v6+

### Frameworks

- Symfony 2.8

## Testé avec

### Logiciels

- PHP :
    - 5.6
    - 7.0
    - 7.1
- MariaDB :
    - 10.1
- Node.js :
    - 6.9
    - 7.4

### Frameworks

- Symfony :
    - 2.8

## Installation (environnement de développement)

Pour créer notre environnement de développement, nous allons installer XAMPP, Symfony et NodeJS (avec NPM).

### XAMPP

Téléchargez l'installateur XAMPP (ensemble de logiciels pour la création et le lancement d'applications web) ici: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)

Vous pouvez choisir la version correspondante à votre système d'exploitation (Windows, Linux, macOS).

Pour Linux, il nous reste plus qu'a changer les permissions de l'installateur...

```
chmod 755 xampp-linux-*-installer.run
```

...et à le lancer

```
sudo /opt/lampp/lampp start
```

### Xdebug

Xdebug est un débogueur pour PHP, il sera utile de l'installer et de le configurer pour le développement.

Copier les informations de `phpinfo()`, en allant par exemple sur ce lien [http://localhost/dashboard/phpinfo.php](http://localhost/dashboard/phpinfo.php), dans le formulaire de la page [https://xdebug.org/wizard.php](https://xdebug.org/wizard.php) pour recevoir des informations adaptées à notre machine.

### Pilote

Passons à l'installation du site proprement dite.

La première étape est le choix du répertoire d'installation. Par exemple, dans un dossier du répertoire utilisateur (`~/`) ou `/opt/htdocs/` pour le mettre dans le répertoire de XAMPP. Ici, on va installer `Pilote` dans le dossier ~/projects/pilote/.

```
mkdir ~/projects/pilote/
cd ~/projects/pilote/
```

On clone le dépôt des sources pour télécharger le code.

```
git clone https://github.com/Jabberock/pilote
```

*Pour utiliser la branche LDAP de Pilote, il faut check-out la branche `ldap` au lieu de `master`.*

Pour que notre serveur Apache installé par XAMPP puisse servir Pilote, nous allons créer un lien entre le dossier des sources et le répertoire contenant les fichiers affichés par Apache.

```
ln -s ~/projects/pilote/ /opt/lampp/htdocs/
```

On peut maintenant mettre à jour `Composer`, c'est le gestionnaire de paquet (fourni) qui va installer les logiciels dont à besoin Pilote.

```
cd sources/

php composer.phar self-update
```

Enfin, nous pouvons lancer l'installation de Pilote. Un script d'installation sera lancé afin de configurer le site.

```
php composer.phar install
```

Ce script vous demandera plusieurs informations afin de compléter le fichier de paramètres. À chaque fois, vous pouvez laisser la valeur par défaut en appuyant sur ```Entrée```.

* ```database_driver : (pdo_mysql)```
 * Laissez la valeur par défaut
* ```database_host : (127.0.0.1)```
 * Idem.
* ```database_port : (null)```
 * Idem.
* ```database_name : (pilote)```
 * Le nom que vous voulez donner à la base de données. Vous pouvez laisser par défaut.
* ```database_user : (root)```
 * Le nom d'utilisateur de la base de données.
* ```database_password : (null)```
 * Le mot de passe de la base de données.
* ```mailer_transport : (smtp)```
 * Laissez la valeur par défaut
* ```mailer_host : (127.0.0.1)```
 * L'adresse IP du serveur de mail utilisé.
* ```mailer_user : (null)```
 * L'adresse mail utilisé pour envoyer des mails (l'expéditeur).
* ```mailer_password : (null)```
 * Le mot de passe associé à cette adresse mail.
* ```locale : (fr)```
 * Sert à la localisation. Actuellement, seul le bundle gérant les utilisateurs est localisé.
* ```secret : (ThisTokenIsNotSoSecretChangeIt)```
 * Ce token sert à la génération des tokens CSRF. Changez cette variable par une chaîne de caractères de votre choix.
* ```debug_toolbar : (true)```
 * Laissez par défaut
* ```debug_redirects : (false)```
 * Idem.
* ```use_assetic_controller : (true)```
 * Idem
* ```router.request_context.base_url: (null)```
 * Cette variable sera ajoutée au début de chaque route dans les fichiers JS.
 * Si vous avez suivi ce tutoriel, il faut écrire ```/app.php```.
* ```notification_connexion_port: ('8010')```
 * Le port de votre serveur Node.JS. Il est définit dans le fichier ```sources/web/js/notifications/app.js```. Laisser par défaut.
* ```disable_registration: (false)```
 * Permet de désactiver les inscriptions sur le site. Cela peut être utile si vous voulez limiter les accès aux seuls utilisateurs provenant de l'annuaire LDAP.

Si vous avez choisi d'installer la branche LDAP de Pilote, vous devrez aussi renseigner les champs suivants :

* ```ldap_host: (    null)```
 * L'adresse IP de votre serveur LDAP.
* ```ldap_port: (    null)```
 * Le port du serveur.
* ```ldap_username: (null)```
* ```ldap_password: (null)```
* ```ldap_baseDN: (null)```
 * Exemple : ```OU=Utilisateurs,OU=Services,DC=entreprise```

### Base de données

Doctrine ORM est une bibliothèque qui va nous permettre d'interagir facilement avec notre base de données.

On va maintenant créer une base de données vide. Son nom est celui saisi comme nom de base de données dans l'installateur.

```
sudo php app/console doctrine:database:create
```

Le remplissage de la structure notre base de données se fait aussi avec Doctrine :

```
sudo php app/console doctrine:schema:update --force
```

### Notifications (serveur Node.js)

Si Node.js est bien installé, nous avons accès à npm, son gestionnaire de paquet.

Nous allons installer socket.io pour les notifications en temps réel.

```
cd web/js/notifications
npm install socket.io
```

On installe aussi pm2, c'est un gestionnaire de processus pour Node.js qui va nous aider pour lancer en production le serveur de notifications.

```
cd ../../..
sudo npm install pm2 -g
```

On peut maintenant démarrer le serveur de notifications via pm2 :

```
pm2 start web/js/notifications/app.js
```

### Routes

Enfin, on génère le fichier de routes et on vide le cache :

```
php app/console fos:js-routing:dump
php app/console cache:clear --env=prod

chmod -R 777 app/logs app/cache app/sessions web/uploads
```

## Installation (documentation historique)

La plupart des commandes suivantes nécessitent les droits super-administrateur, on supposera donc que vous êtes connecté en tant que ```root```.
Tout d'abord, vérifions que le serveur est à jour :

```shell
apt-get update
apt-get upgrade
```

A présent installons les packages nécessaires :

```shell
apt-get install apache2 php5 mysql-server libapache2-mod-php5 php5-mysql curl php5-curl php5-ldap git php5-intl
```

Il faut aussi installer les packages _node_ et _npm_, mais dans Ubuntu par exemple il est disponible dans une version trop ancienne. Nous avons besoin de la version 0.10 minimum. Il faut donc passer par une méthode d'installation alternative :

### Première méthode d'installation de _node_ : via le dépôt GitHub

```shell
git clone https://github.com/joyent/node.git
cd node
./configure
make
make install
```

### Deuxième méthode : via un dépôt externe

```shell
curl -sL https://deb.nodesource.com/setup | sudo bash -
sudo apt-get install nodejs build-essential
```

Lançons à présent les services Apache et MySQL :

```shell
service apache2 start
service mysql start
```

Passons à l'installation du site proprement dite. On l'installera dans le dossier ```/var/www/```. On clone le dépôt GitHub et on lance le script d'installation. Pour utiliser la branche LDAP, il faut remplacer ```master``` par ```ldap``` :

```shell
cd /var/www/
git clone -b master https://github.com/Artemis-Haven/pilote.git
cd pilote/sources
php composer.phar self-update
php composer.phar install
```

Ce script vous demandera plusieurs informations afin de compléter le fichier de paramètres. A chaque fois, vous pouvez laisser la valeur par défaut en appuyant sur ```Entrée```.

* ```database_driver : (pdo_mysql)```
 * Laissez la valeur par défaut
* ```database_host : (127.0.0.1)```
 * Idem.
* ```database_port : (null)```
 * Idem.
* ```database_name : (pilote)```
 * Le nom que vous voulez donner à la base de données. Vous pouvez laisser par défaut.
* ```database_user : (root)```
 * Le nom d'utilisateur de la base de données.
* ```database_password : (null)```
 * Le mot de passe de la base de données.
* ```mailer_transport : (smtp)```
 * Laissez la valeur par défaut
* ```mailer_host : (127.0.0.1)```
 * Idem.
* ```mailer_user : (null)```
 * Idem.
* ```mailer_password : (null)```
 * Idem.
* ```locale : (fr)```
 * Sert à la localisation. Actuellement, seul le bundle gérant les utilisateurs est localisé.
* ```secret : (ThisTokenIsNotSoSecretChangeIt)```
 * Ce token sert à la génération des tokens CSRF. Changez cette variable par une chaîne de caractères de votre choix.
* ```debug_toolbar : (true)```
 * Laissez par défaut
* ```debug_redirects : (false)```
 * Idem.
* ```use_assetic_controller : (true)```
 * Idem
* ```router.request_context.base_url: (null)```
 * Cette variable sera ajoutée au début de chaque route dans les fichiers JS.
 * Si vous avez suivi ce tutoriel, il faut écrire ```/app.php```.
* ```notification_connexion_port: ('8010')```
 * Le port de votre serveur Node.JS. Il est définit dans le fichier ```sources/web/js/notifications/app.js```. Laisser par défaut.
* ```disable_registration: (false)```
 * Permet de désactiver les inscriptions sur le site. Cela peut être utile si vous voulez limiter les accès aux seuls utilisateurs provenant de l'annuaire LDAP.

Si vous avez choisi d'installer la branche LDAP de Pilote, vous devrez aussi renseigner les champs suivants :

* ```ldap_host: (    null)```
 * L'adresse IP de votre serveur LDAP.
* ```ldap_port: (    null)```
 * Le port du serveur.
* ```ldap_username: (null)```
* ```ldap_password: (null)```
* ```ldap_baseDN: (null)```
 * Exemple : ```OU=Utilisateurs,OU=Services,DC=entreprise```

Ensuite on crée la base de données :

```shell
sudo php app/console doctrine:database:create
sudo php app/console doctrine:schema:update --force
```

Ensuite on va s'occuper du serveur _node_. On va installer _socket.io_ pour les notifications en temps réel, et _pm2_ pour gérer le processus plus simplement sur un serveur de production. Puis on va démarrer le processus avec _pm2_.

```shell
cd web/js/notifications
npm install socket.io
cd ../../..
npm install pm2 -g
pm2 start web/js/notifications/app.js
```

Enfin, on génère le fichier de routes et on vide le cache :

```shell
php app/console fos:js-routing:dump
php app/console cache:clear --env=prod
chmod -R 777 app/logs app/cache app/sessions web/uploads
```

#### Configuration d'Apache

La dernière étape est la configuration d'Apache pour rediriger directement vers notre page d'accueil (en l'occurrence, dans ```sources/web/app.php```).

Créez un nouveau fichier de configuration :
```shell
> nano /etc/apache2/sites-available/pilote.conf
```
Et ajoutez le contenu suivant :
```shell
<VirtualHost *:80>
    ServerName projet-pilote.fr
    DocumentRoot "/var/www/pilote/sources/web"
    DirectoryIndex app.php
    <Directory "/var/www/pilote/sources/web">
        AllowOverride All
        Allow from All
    </Directory>
</VirtualHost>
```

Activez le nouveau VHost et redémarrez Apache :
```shell
> a2ensite pilote.conf
> a2enmod rewrite
> service apache2 restart
```

#### Créer un utilisateur depuis la console

Si vous avez désactivé la possibilité de s'inscrire sur le site (```disable_registration: true``` dans le fichier ```app/config/parameters.yml```), vous aurez besoin de créer un premier administrateur.

Pour créer un utilisateur :
```shell
> php app/console fos:user:create
Please choose a username: Admin
Please choose an email: admin@admin.com
Please choose a password:
Created user Admin
```

Pour promouvoir un utilisateur en administrateur :
```shell
> php app/console fos:user:promote
Please choose a username: Admin
Please choose a role: ROLE_ADMIN
Role "ROLE_ADMIN" has been added to user "Admin".
```

Pour rétrograder un administrateur en simple utilisateur :
```shell
> php app/console fos:user:demote
Please choose a username: Admin
Please choose a role: ROLE_ADMIN
Role "ROLE_ADMIN" has been removed from user "Admin".
```

## Maintenance

Voir la [page concernée](maintenance.md).