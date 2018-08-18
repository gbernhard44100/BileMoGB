BileMoGB API
========

## Context:
This is a Symfony project I have launched on May 24 in 2018, in order to meet the next specifications : [Specifications for project 7 Parcours developer PHP / Symfony](https://openclassrooms.com/fr/projects/creez-un-web-service-exposant-une-api).

## Code quality:
Link of the code analysis made by Codacy : [![Codacy Badge](https://api.codacy.com/project/badge/Grade/5e1321b940f641d692d7256b25d26719)](https://www.codacy.com/app/gbernhard44100/snowtricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=gbernhard44100/snowtricks&amp;utm_campaign=Badge_Grade) <- reached on the 13 May 2018

## Main Code / frameworks / Libraries used for this project :
* PHP 7.2
* Symfony 3.4
* JMS Serializer 
* Bazinga Hateoas
* Nelmio api-doc-bundle 3.0
* JWT for Bearing Token

## Install:
1. clone this Git repository in your project folder
2. Copy and Rename the file **parameters.yml.dist** by **parameters.yml** in the folder *app/config*.
3. Open the command terminal, go to your project folder and launch the command : composer install 
   Your terminal will ask you to fill the next information :
    * database_host: *IP address of the server where your database is*
    * database_port: null
    * database_name: *name of your database*
    * database_user: *username to connect to your database*
    * database_password: *password to connect to your database*
    * secret: *a string which has to be unique for each of your application* 
    For the next configuration to fill, keep the configuration by default.
4. Create the database from your symfony project by using the next code on your terminal:  
*php bin/console doctrine:database:create*
5. Update the database by using the next code on your terminal: *php bin/console doctrine:schema:update --force*
6. Load the content of your database by using the next code on your terminal : *php bin/console doctrine:fixtures:load*  
and type **y**
7. Load the compiled css and js files by using the next code on your terminal :  
*php bin/console assetic:dump* if you  want to use in dev mode  
or *php bin/console assetic:dump --env=prod* if you want to use in production

## Usage:
Before using this API, you can read this documentation : [BileMoGB documentation](http://bilemogb.bernharddesign.ovh/api/doc)

## ENJOY!!!