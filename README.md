### Администрирование ТГ-каналов
## Установка
- Создать .env и скопировать всё из .env.example
- Добавить TELEGRAM_BOT_KEY и TELEGRAM_BOT_NAME в .env
- Выполнить установку с помощью docker
```shell
docker-compose up --build -d
```
Или
```shell
make build
```
- Зайти в контейнер php-telegram-admin и выполнить
```shell
php artisan migrate --seed
php artisan storage:link
```