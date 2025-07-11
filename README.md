## Установка приложения

#### Установка зависимостей:
```sh
composer install
npm install
```

#### Копирование переменных окружения:
```sh
cp .env.example .env
```
После копирования, настройте параметры подключения к БД
(DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

#### Генерация ключа приложения:
```sh
php artisan key:generate
```

#### Выполните миграции:
```sh
php artisan migrate --seed
```
(Вместо встроенных сидеров, можно использовать скрипты из [проекта](https://github.com/tewln/db_project "Проект БД, на основе которого построен сайт"))

#### Соберите фронтенд:
```sh
npm run build
```

#### Запустите сервер:
```sh
php artisan serve
```

##### После исполнения всех команд, сервер должен запуститься по [адресу 127.0.0.1:8000](http:/127.0.0.1:8000)