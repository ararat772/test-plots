# Запуск

## Запуск с нуля
- docker-compose up -d

чтобы накатит миграци делаем

- docker exec -it server-php bash

когда уже находимся  внутри  контейнера делмаем

- php artisan migrate

для того чтобы сделать запросы в Postman делаем следующее

- docker inspect server-php

после чего копируем IPAddress, например: 172.30.0.2

- Достпуна по адресу например: http://172.30.0.2:80

## Доступы к БД

Сначала нужно получить IPAddress MySQL.
Для этого запускаем следующую команду

- docker inspect server-mysql

аналагичном образом  кампируем IPAddress и этот IP вставляем вместо 'localhost'


БД: test

Юзер: admin

Пароль: secret

Порт: 3306

![plot](./image/Screenshot%20from%202023-10-25%2001-11-44.png)
![plot](./image/Screenshot%20from%202023-10-25%2003-27-19.png)
![plot](./image/Screenshot%20from%202023-10-25%2003-27-57.png)

