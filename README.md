## CONTEXT :
Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).

Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.

Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy. Votre mission : créer un site communautaire pour apprendre les figures de snowboard

## DESCRIPTION DU BESOIN:
Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes :

un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
la gestion des figures (création, modification, consultation) ;
un espace de discussion commun à toutes les figures.
Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

- la page d’accueil où figurera la liste des figures ; 
- la page de création d'une nouvelle figure ;
- la page de modification d'une figure ;
- la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).
- L’ensemble des spécifications détaillées pour les pages à développer est accessible ici : Spécifications détaillées.

## Configuration requise : 

Language => PHP 8.2.4

Database => 10.4.28-MariaDB

Web Server => xampp, wampp etc..

Framework => Symfony 6.1.12

Composer => 2.2.22

## Installation :
Clonez ou téléchargez le repository GitHub dans le dossier voulu :

```text
git clone https://github.com/KarolZ13/P6-OCR.git
```

Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier .env.local qui devra être crée à la racine du projet en réalisant une copie du fichier .env.

Téléchargez et installez les dépendances back-end du projet avec Composer :

```text
composer install
```

Créez la base de donnée :

```text
php bin/console doctrine:database:create
```

Mettez à jour vos tables : 

```text
php bin/console doctrine:schema:update --force
```

(Optionnel) Installer les fixtures pour avoir une démo de données fictives :

```text
php bin/console doctrine:fixtures:load
```

Lancez le serveur à l'aide de la commande suivante:

```text
symfony server:start -d
```

Si vous utilisez les fixtures, voici comment se connecter avec un compte utilisateur :

Le nom d'utilisateur et le mot de passe sont identiques, par exemple:

```text
Nom d'utilisateur: jean.dupont
Mot de passe: jean.dupont
```

Félications le projet est correctement installé!
