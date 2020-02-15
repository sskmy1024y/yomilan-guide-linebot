# yomiran-guide-linebot

よみうりランド案内用LINE Bot

## Usage

```sh
cp .env.example .env
cp app/.env.example app/.env
docker-compose build
docker-compose rum --rm app composer install
docker-compose rum --rm app php artisan migrate:fresh --seed
```

### Create new project

```sh
rm ./app/.gitkeep
docker-compose run --rm app composer create-project --prefer-dist laravel/laravel .
```

#### TODO

Make `docker-compose.production.yml`
