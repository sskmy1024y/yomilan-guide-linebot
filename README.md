# yomiran-guide-linebot

よみうりランド案内用LINE Bot

## Usage

```sh
cp .env.template .env
docker-compose build
```

### Create new project

```sh
rm ./app/.gitkeep
docker-compose run --rm app composer create-project --prefer-dist laravel/laravel .
```

#### TODO

Make `docker-compose.production.yml`
