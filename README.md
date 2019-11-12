# Unity WP Starter Template

## Setup Local Environment

Create new project in Local by Flywheel:
* Set site name, local dev URL, and project directory
* Choose custom environment:
  * PHP Version >= 7.2.0
  * Web server = Apache
  * MySQL >= 5.6

## Create Git Repo From Template

1. Create a new repository in Unity's GitHub organization: https://github.com/organizations/unitymakesus/repositories/new (**Do not initialize it with a README, license, or .gitignore files.**)

2. Then clone the starter-template repo without commit history. Move the starter-template files into the project directory and initialize a new repo for the new project:

````shell
# @ app/public/
$ git clone --depth 1 https://github.com/unitymakesus/starter-template.git
$ rm -rf starter-template/.git
$ cp -r starter-template/. .
$ rm -rf starter-template
$ git init
$ git add .
$ git commit -m "Initial commit"
$ git remote add origin [replace with remote repository URL]
$ git remote -v
$ git push -u origin master
````

## Requirements

Make sure all dependencies have been installed:

* [PHP](http://php.net/manual/en/install.php) >= 7.1.3 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 6.9.x
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme development

* Run `yarn` from the theme directory to install dependencies
* Update `resources/assets/config.json` settings:
  * `devUrl` should reflect your local development hostname
  * `publicPath` should reflect your WordPress folder structure (`/wp-content/themes/sage` for non-[Bedrock](https://roots.io/bedrock/) installs)

### Build commands

* `yarn start` — Compile assets when file changes are made, start Browsersync session
* `yarn build` — Compile and optimize the files in your assets directory
* `yarn build:production` — Compile assets for production

## Documentation

* [Sage documentation](https://roots.io/sage/docs/)
* [Controller documentation](https://github.com/soberwp/controller#usage)
