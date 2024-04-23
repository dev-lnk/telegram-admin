### Администрирование ТГ-каналов
![logo](https://raw.githubusercontent.com/dev-lnk/telegram-admin/master/public/images/admin.png)
## Установка
- Создать .env и скопировать всё из .env.example
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
или выполнить из терминала
```shell
make install
```
- Перейти на http://localhost/login, Пользователь: admin@mail.ru, пароль: 12345