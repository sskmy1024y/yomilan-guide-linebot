# yomiran-guide-linebot

よみうりランド案内用LINE Bot

## Usage

```sh
cp .env.example .env
cp app/.env.example app/.env
docker-compose build
docker-compose run --rm app bash
  COMPOSER_MEMORY_LIMIT=-1 composer install
  exit
docker-compose up -d
docker-compose exec app bash
  php artisan migrate:fresh --seed
```

## Q&A

#### Q. `composer install`時に`killed`が出る

メモリ不足による強制終了らしい。これはdocker自体の割当メモリを増やすことで解決できた。
