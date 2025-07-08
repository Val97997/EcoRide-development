
# Aditional Packages

We need dompdf for PDF ticket reservation generation :
## $ composer require dompdf/dompdf

Animejs component for dynamic and modular animations :
## $ npm install animejs

Mandatory sass and ts loaders from Node packages :
## $ npm install typescript ts-loader@^9.0.0 --save-dev && npm install sass-loader sass webpack --save-dev

Mailer Component required for automated booking emails :
## composer require  symfony/mailer
## composer require symfony/mailtrap-mailer

### VERY IMPORTANT : comment the following line (l 24 of messenger.yaml) or emails from Mailer comp wont get through !

Install the NoSQL setup to connect to MongoDB :
# $ composer require doctrine/mongodb-odm-bundle

For testing with phpUnit :
# $ composer require --dev phpunit/phpunit

We have installed an extension for access to DQL higher functions for our queries (needed for Search page filters)
# $ composer require beberlei/doctrineextensions

Necessary for inlining CSS files in our mail templates :
# $ composer require twig/cssinliner-extra

Installing chart.js for admin panel charts:
# $ npm install chart.js

Run Docker :
# $ docker-compose build && docker-compose up -d

Deployment check:
# $ composer require symfony/requirements-checker

ADMIN USER CREATION :
    In order to process the Admin profile creation, NAVIGATE to localhost/creatAdmin (only accessible if logged in => security.yaml config),
    this will execute a custom pure SQL script and create the Admin profile. @Todo : think of a better and safer method for implementing Admin profile

 >> We have set up connection for the Doctrine component to MongoDB for the NoSQL part of the databases, which
 we will be using for storing the destination list as verbose descriptive files.

 >> We also need to create a custom SQL query attached to a controller in order to create the Admin User Profile, and feed it
 some values (INSERT INTO [...](...) VALUES (...))