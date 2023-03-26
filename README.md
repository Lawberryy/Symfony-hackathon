<h3 align="center">Ce projet a été réalisé en une semaine</h3>

# <p align="center">Projet hackathon "Ça glisse"</p>

Ce projet avait pour objectif de nous mettre au défi sur nos compétences d'organisation et de nous permettre de mettre en pratique les compétences acquises cette année. Il s'agit de la création d'un site web axé sur les domaines et les stations de ski, réalisé avec le framework Symfony. Pour générer rapidement des idées et des solutions créatives, nous avons mis en place des méthodes de Design Thinking. <br>

### Les deux grandes fonctionnalités du site 
- Tchat pour discuter avec les utilisateurs sur le site 
- Création de parcours personnalisés 

## Fonctionnalités attendues
### Partie back-office  
- Base de données avec domaines, stations, pistes, remontées et utilisateurs 
- Inscription et connexion
- Administration (avec EASY ADMIN)
- Fixtures pour l'utilisation en local
- Admin et Super admin

### Partie front-office 
- Page d'accueil
- Page de la liste des domaines
- Page du domaine avec la liste de ses stations
- Page d'une station 
<hr>

## Fonctionnalités trouvées par l'équipe 
### Partie back-office  
- Création de circuits
- Messagerie instantanée avec historique des conversations
- Création de Rapports

### Partie front-office 
- Affichage pour créer des circuits
- Affichage de la messagerie
- Animation météo
- Rapport des problèmes via formulaire


# <p align="center">Setup Symphony</p>
```bash
composer install
```

<hr>


# <p align="center">Setup db</p>

## 🔗 Link db

Copier le fichier ``.env`` en fichier ``env.local``
<br>
Commenter la l.28
<br>
Décommenter la l.27 et changer les infos de la db, puis la créer

## 🛠️ Create table
```bash
php bin/console make:entity
```
Choisir le nom de sa table et de ses colones

## 🛠️ Create db and push
       
```bash
php bin/console doctrine:database:create
```

```bash
php bin/console make:migration
```

```bash
php bin/console doctrine:migrations:migrate
```
<hr>



```bash
php bin/console make:fixture
```
Rentrer les infos des tables dans le fichier ``src/DataFixtures/AppFixtures.php``
```bash
php bin/console doctrine:fixture:load
```

# <p align="center">Lancer le Chat en Direct </p>

Dans un nouveau terminal :
```bash
php bin/console run:websocket
```

# <p align="center">Identifiants  </p>

Super Admin :

> Super.User@gmail.com | viveleski

Admin :

> firstname1.lastname1@gmail.com | vivelamontagne