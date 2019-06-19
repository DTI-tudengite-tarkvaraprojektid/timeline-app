# Timeline app

 ![Screenshot](/assets/screenshot1.png?raw=true "Screenshot")

#### Eesmärk või lühikirjeldus

Eesmärgiks oli luua tööriist kuhu saaks instituudi liikmed lisada tekste, fotosid, videoid ja faile ning neid otsida märksõnade järgi. Luua ka ajajoon kuhu saaks lisada sündmusi ja sündmuste sisse pilte, faile ja videosi.

#### Tallinna Ülikooli Digitehnoloogiate instituut
See projekt on loodud suve praktika raames.

#### Kasutatud tehnoloogiad
- PHP : versioon 7.2
- MariaDB : versioon 10.1
- NodeJs : versioon 11.1
- Apache 2

**PHP teegid**
- Slim 3 : versioon 3.0
- Slim Flash : versioon 0.2.0
- Slim Validation : versioon
- Sentinel : versioon 2.0
- Slim twig view : versioon 2.0
- Eloquent : versioon 5.4

**NodeJs teegid**
- webpack : versioon 4.32
- bootstrap : versioon 4.3
- jquery : versioon 3.4
- quill : versioon 1.3
- tippy.js : versioon 4.3

#### Arendajad
- Marvin Helstein
- Roland Vägi
- David Frederik Erlich
- Taavi Liivat
- Steven Saluri

# Paigaldus

# Slim 3 application skeleton

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/297ce2e4-166d-45d5-8d11-ae0651a8c7ac/mini.png)](https://insight.sensiolabs.com/projects/297ce2e4-166d-45d5-8d11-ae0651a8c7ac) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/awurth/Slim/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/awurth/Slim/?branch=master)

This is an app skeleton for the Slim PHP Micro-Framework to get started quickly

## Features
- [Eloquent ORM](https://github.com/illuminate/database)
- Flash messages ([Slim Flash](https://github.com/slimphp/Slim-Flash))
- CSRF protection ([Slim Csrf](https://github.com/slimphp/Slim-Csrf)) with fields rendering with a twig function
- Authentication ([Sentinel](https://github.com/cartalyst/sentinel))
- Validation ([Respect](https://github.com/Respect/Validation) + [Slim Validation](https://github.com/awurth/slim-validation))
- Twig templating engine [Slim Twig View](https://github.com/slimphp/Twig-View) with cache and debug
- CSS Framework [Semantic UI](https://github.com/Semantic-Org/Semantic-UI)
- A **Gulpfile** with a watcher for *SASS* and *JS* files, and minification
- Helpers for assets management, redirections, ...
- Logs ([Monolog](https://github.com/Seldaek/monolog))
- Dotenv configuration
- Console commands for updating the database schema or creating users
- Functionnal tests base ([PHPUnit](https://github.com/sebastianbergmann/phpunit))

For more information, check out the project's [website](http://awurth.fr/doc/boilerplate/slim) or the [wiki](https://github.com/awurth/Slim/wiki).

## Paigaldus
### Create the project using Composer
``` bash
$ composer create-project awurth/slim-base [project-name]
```

### Setup environment variables

Copy `.env.dist` to a `.env` file and change the values to your needs. This file is ignored by Git so all developers working on the project can have their own configuration.

### Download client-side libraries
``` bash
$ npm install
```
This will install Gulp dependencies and Semantic UI in `public/assets/lib/semantic/`.


#### Generate assets
If you just want to generate the default CSS and JS that comes with this skeleton, run the following command
``` bash
$ gulp build
```

If you want to run a watcher and begin coding, just run
``` bash
$ gulp
```

### Setup cache files permissions
The skeleton uses a cache system for Twig templates and the Monolog library for logging, so you have to make sure that PHP has write permissions on the `var/cache/` and `var/log/` directories.

### Update your database schema
First, create a database with the name you set in the `.env` file. Then you can create the tables by running this command:
``` bash
$ php bin/console db
```
[MIT License for this](LICENSE)
