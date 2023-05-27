# Readme pour le projet Blog OpenClassrooms

Ce projet est un blog créé dans le cadre d'un cours OpenClassrooms. Il utilise Composer pour gérer ses dépendances et dispose d'une intégration Codacy pour surveiller la qualité du code.

## Installation


Pour installer ce projet, il suffit de cloner le dépôt et d'installer les dépendances avec Composer :
```bash
git clone https://github.com/ton-repo/Blog_OCR.git
cd Blog_OCR
composer install
```
    

## Utilisation

Le projet utilise un système de routage personnalisé. Pour que les routes fonctionnent correctement, vous devez modifier la 23ème ligne du fichier `Router/Router.php` pour correspondre au chemin de votre projet sur votre serveur :

```php
$path = str_replace("/chemin/vers/votre/projet", "", $path);
```

Par exemple, si votre projet est accessible à l'adresse http://localhost/Blog_OCR/, vous devez modifier la ligne comme suit :

```php
$path = str_replace("/Blog_OCR", "", $path);
```

Le site est ensuite accessible à l'adresse http://localhost/votre-projet/public.

## Intégration Codacy
Ce projet dispose d'une intégration Codacy pour surveiller la qualité du code. Vous pouvez consulter le rapport en cliquant sur le badge ci-dessous :

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/be30f7b34b184566adceef8ad0a50bd8)](https://app.codacy.com/gh/BenDejardin/Blog_OCR/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## PHPStan
![PHPStan](https://raw.githubusercontent.com/BenDejardin/Blog_OCR/main/diagrammes/phpstan.png)
