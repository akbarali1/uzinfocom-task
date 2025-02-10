# Installation

1. `docker` folder run command `docker compose up --build -d`
2. `php_onlyoffice` folder run command `make compose-prod`
3. Install composer inside the docker container
4. Run command `docker exec -it uzinfocom_task_app bash`
5. Run command `composer install`
6. Run command `cp .env.example .env`
7. Run command `php artisan key:generate`
8. You can enter your telegram ID in `TELEGRAM_LOGGER_CHAT_ID`. If you want, you can also enter the Bot token to enable logging from your bot. To do this, enter the bot token in `TELEGRAM_LOGGER_BOT_TOKEN`. Test Logging Bot username [@uzinfokomrobot](http://t.me/uzinfokomrobot)
9. Run migrations `php artisan migrate --seed`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
