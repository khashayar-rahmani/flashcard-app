# Flashcard Game

This is an interactive console command application for managing and practicing flashcards.


## Running the application
First run the following command: 
```
composer install
```
Then if you have any local config, navigate into the root of the project and copy .env.example as .env. Fill your local config in .env file.
For example if you got the following error:
```
Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use
```
Just have an entry in you .env file saying `APP_PORT=8080` (or any other port that you want.)

To set up the whole stack, just issue the following command in the root of the project.

```
./vendor/bin/sail up
```

The database port is set to 3307 of the host machine.

## Usage
To run the Flashcard game, issue the following command in the command line:
```
php artisan flashcard:interactive
```
After that you see a menu to continue interacting with the application.
## Tests

To run unit tests, just issue the following command:

```
php artisan test --testsuite=Feature
```
