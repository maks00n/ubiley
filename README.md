## Локальный запуск в Docker

Ниже — краткая инструкция по развёртыванию проекта на локальной машине с помощью Docker.

### Предварительные требования

-   **Docker Desktop** (Windows/macOS) или **Docker Engine** (Linux)
-   Для Windows: рекомендуется WSL2 backend в Docker Desktop

### Структура сервисов

-   **php**: PHP-FPM 8.3, контейнер `api_php`
-   **web**: Nginx, контейнер `api_web`, порт `80:80`
-   **db**: MySQL 8.0, контейнер `api_db`, порт `3306:3306`

### Переменные окружения

Проект использует переменные окружения из `.env` / `.env.local` и пробрасывает их в контейнеры. Минимально необходимый набор:

```env
# Symfony
APP_ENV=dev
APP_SECRET=ChangeMeToSomethingRandom

# БД (MySQL внутри Docker сети доступна по хосту "db")
MYSQL_ROOT_PASSWORD=changeme-root
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=changeme

# Doctrine использует эту строку подключения
DATABASE_URL="mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@db:3306/${MYSQL_DATABASE}?serverVersion=8.0&charset=utf8mb4"

# Данные для команды создания админа
ADMIN_LOGIN=admin
ADMIN_PASSWORD=admin123
```

Создайте файл `.env.local` (или отредактируйте `.env`) в корне проекта и вставьте значения. Для локальной разработки удобно использовать `.env.local`.

### Первый запуск

1. Собрать и запустить контейнеры:

```bash
docker compose up -d --build
```

2. Установить зависимости Composer (внутри контейнера PHP):

```bash
docker compose exec php composer install --no-interaction
```

3. Применить миграции:

```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### Доступы и URL

-   **Приложение (Nginx)**: `http://localhost/`
-   **API Platform**: `http://localhost/api` и Swagger UI по адресу `http://localhost/api/docs`
-   **MySQL**: `localhost:3306` (пользуйтесь хостом `db:3306` внутри сети Docker)

### Полезные команды

-   Перезапуск сервисов:

```bash
docker compose restart
```

-   Остановка и удаление контейнеров (данные БД сохранятся в volume `db_data`):

````bash
docker compose down
````

- Полная очистка с удалением volume БД (удалит все данные!):

```bash
docker compose down -v
````

-   Просмотр логов:

```bash
docker compose logs -f | cat
```
