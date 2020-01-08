# Dockerfile-Template-for-Laravel

Docker上にLaravel環境を構築するテンプレート

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
