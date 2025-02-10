# Installation

1. git clone project `git clone git@github.com:akbarali1/uzinfocom-task.git`
2. Give the necessary permission. `chown -R 1000:1000 uzinfocom-task` and `cd uzinfocom-task/docker`
3. Run command `docker compose up --build -d`
4. `php_onlyoffice` folder run command `make compose-prod`
5. Install composer inside the docker container
6. Run command `docker exec -it uzinfocom_task_app bash`
7. Run command `composer i`
8. Run command `cp .env.example .env`
9. Run command `php artisan key:generate`
10. You can enter your telegram ID in `TELEGRAM_LOGGER_CHAT_ID`. If you want, you can also enter the Bot token to enable logging from your bot. To do this, enter the bot token in `TELEGRAM_LOGGER_BOT_TOKEN`. Test Logging Bot username [@uzinfokomrobot](http://t.me/uzinfokomrobot)
11. Run migrations `php artisan migrate --seed`
12. You need to configure only office to the specified location in the `docker/docker-compose.yml` file. onlyoffice runs on port 8080. In my case, I have forwarded the domain office.webschool.uz to port 8080.

Example Nginx config:

```
server {
    listen 80;
    server_name uzinfocom.webschool.uz www.uzinfocom.webschool.uz;

    location /.well-known/acme-challenge/ {
        root /var/www/html;
    }

    location / {
        proxy_pass http://127.0.0.1:8005; # Asosiy sayt 8005-portda ishlaydi
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Real-IP $remote_addr;
    }
}

server {
    listen 80;
    server_name office.webschool.uz;

    location /.well-known/acme-challenge/ {
        root /var/www/html;
    }

    location / {
        proxy_pass http://127.0.0.1:8080/; # 8080-portdagi xizmatga proxy
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_redirect off;
    }
}

server {
    listen 443;
    server_name uzinfocom.webschool.uz www.uzinfocom.webschool.uz office.webschool.uz;

    return 301 http://$host$request_uri; # HTTPS'dan HTTP'ga yo‘naltirish
}
```

If everything went well, the site should be up and running at http://localhost:8005.

# What's up?

1. Role management system `admin` and `user`
2. Sending logs via Telegram bot and keeping daily logs to yourself.
3. Create, edit, view, upload and delete word files using [OnlyOffice](https://www.onlyoffice.com/)
4. A user can only view, edit, upload and delete files they have created.
5. The administrator can create, upload, and view, delete, and edit Word files uploaded by other users.
6. Only admins can create users.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
