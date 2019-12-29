# Poker hands

## How to run

### Pre-requisites
- Docker
- Docker Compose

### Setup

Clone this repo and run the following commands, in the given order:

1. `cp .env.example .env`
2. `docker-compose up -d`
3. `docker-compose exec united_remote_web composer install`
4. `docker-compose exec united_remote_web php artisan key:generate`
5. `docker-compose exec united_remote_web php artisan migrate`

> Note that the application uses the ports 80 and 3306 in order to run, so make sure no other service is using those ports.

The application will be up and running at [http://localhost]() or [http://127.0.0.1]().

You need to register first in order to use the application.

## Assessment

The file hands.txt, contains 1000 random hands dealt to two players.
Each line of the file contains ten cards (space separated). the
first five are Player 1's cards and the last five are Player 2's cards. You can
assume that all hands are valid, each player's hand is in no specific order and
in each hand there is a clear winner.

Build a PHP MVC solution that has the following Functionality:

1. Upload the file
2. Parse file into a DB
3. have a way to show how many hands player 1 wins
4. Authentication

Please upload the completed solution with a README.md of how to get everything
up and running to github.

I have attached a sheet with the poker rules if you require them. If you need
anything feel free to drop me an email joe@griffin.pm.
