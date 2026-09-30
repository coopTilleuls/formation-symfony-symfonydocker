# Formation Symfony – Approfondir

Projet de départ des exercices de la formation : **Symfony 7.4 LTS** avec le pack `webapp`
(Twig, Doctrine ORM, Form, Validator, Security, MakerBundle, PHPUnit…).

- Base de données **SQLite** : fichier `data/app_dev.db`, créé automatiquement (rien à installer).
- Jeu de données des exercices : `data/books.json`.
- Environnement Docker fourni par [symfony-docker](https://github.com/dunglas/symfony-docker) (FrankenPHP + Caddy, HTTPS, PHP 8.5).

Deux façons de lancer le projet : **avec Docker** (recommandé) ou **sans Docker**. Le code et les exercices sont identiques.

---

## Option A — Avec Docker (recommandé)

**Prérequis** : [Docker Desktop](https://www.docker.com/products/docker-desktop/) (ou Docker Engine + Compose v2.10+).

> **Windows** : activez WSL2 et clonez le projet **dans le système de fichiers Linux** (`~/...` dans votre terminal Ubuntu),
> pas dans `C:\` ni `/mnt/c/...`, sinon tout sera très lent.

```bash
git clone <url-du-depot> formation-symfony-approfondir
cd formation-symfony-approfondir
docker compose build --pull
docker compose up --wait
```

Ouvrez **https://localhost** et acceptez le certificat auto-signé : la page « Welcome to Symfony! » s'affiche.
Le premier démarrage installe les dépendances Composer dans le conteneur (1 à 2 minutes).

Arrêter le projet : `docker compose down --remove-orphans`

**Ports 80/443 déjà utilisés ?**

```bash
HTTP_PORT=8080 HTTPS_PORT=8443 HTTP3_PORT=8443 docker compose up --wait
```

puis ouvrez https://localhost:8443.

---

## Option B — Sans Docker

**Prérequis** : PHP **8.2 ou plus** avec les extensions `ctype`, `iconv`, `intl`, `pdo_sqlite`, `zip`, et [Composer](https://getcomposer.org/download/).
La [Symfony CLI](https://symfony.com/download) est recommandée (`symfony check:requirements` vérifie votre PHP).

```bash
git clone <url-du-depot> formation-symfony-approfondir
cd formation-symfony-approfondir
composer install
symfony server:start
```

Sans la Symfony CLI : `php -S localhost:8000 -t public` puis ouvrez http://localhost:8000.

---

## Aide-mémoire des commandes

Avec Docker, toutes les commandes s'exécutent **dans le conteneur** `php` : c'est lui qui contient PHP, Composer et les extensions.

| Action | Avec Docker | Sans Docker |
|---|---|---|
| Console Symfony | `docker compose exec php bin/console …` | `php bin/console …` |
| Composer | `docker compose exec php composer …` | `composer …` |
| Tests | `docker compose exec php bin/phpunit` | `php bin/phpunit` |
| Couverture de code | `docker compose exec -e XDEBUG_MODE=coverage php bin/phpunit --coverage-html coverage` | `XDEBUG_MODE=coverage php bin/phpunit --coverage-html coverage` (Xdebug ou PCOV requis) |
| Shell dans le conteneur | `docker compose exec php bash` | — |
| Logs | `docker compose exec php tail -f var/log/dev.log` | `tail -f var/log/dev.log` |

Astuce Docker : `alias dc='docker compose exec php'` puis `dc bin/console make:controller`.

---

## Bon à savoir

- **Rechargement du code (Docker)** : FrankenPHP garde l'application en mémoire (*worker mode*) et la recharge
  automatiquement quand un fichier change. En cas de doute : `docker compose restart php`.
- **`var/` (Docker)** : le cache et les logs restent dans le conteneur (meilleures performances) et ne sont pas visibles
  depuis votre machine. Utilisez la commande « Logs » ci-dessus ou le Profiler (barre de debug en bas des pages).
- **Linux + Docker** : si vous ne pouvez pas modifier des fichiers générés par le conteneur (ex. `make:*`) :
  `docker compose run --rm php chown -R $(id -u):$(id -g) .`
- **Xdebug pas à pas (Docker)** : `XDEBUG_MODE=develop,debug docker compose up --wait`
  (voir la [doc symfony-docker](https://github.com/dunglas/symfony-docker/blob/main/docs/xdebug.md)).
- **Remettre la base à zéro** : supprimez `data/app_dev.db`, puis relancez vos migrations
  (`bin/console doctrine:migrations:migrate`).
- **Formulaires et Turbo** : le pack `webapp` active Symfony UX Turbo, qui soumet les formulaires sans recharger la page.
  Pour désactiver ce comportement sur un formulaire : `{{ form_start(form, {attr: {'data-turbo': 'false'}}) }}`.

## Structure

```
data/books.json      Données des exercices (livres : id, title, author)
src/                 Votre code (contrôleurs, services, entités…)
templates/           Vues Twig
tests/               Tests PHPUnit
config/              Configuration Symfony
Dockerfile, compose*.yaml, frankenphp/   Environnement Docker (symfony-docker)
```
