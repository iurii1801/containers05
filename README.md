# Лабораторная работа №5: Запуск сайта в контейнере

## Цель работы

Закрепить навыки работы с Docker-контейнерами, настройкой веб-сервера Apache2, интерпретатора PHP, базы данных MariaDB, системой управления процессами Supervisor, а также развертыванием CMS WordPress.

## Задание

Создать Dockerfile для сборки образа контейнера, который будет содержать веб-сайт на базе Apache HTTP Server + PHP (mod_php) + MariaDB. База данных MariaDB должна храниться в монтируемом томе. Сервер должен быть доступен по порту 8000.

Установить сайт WordPress. Проверить работоспособность сайта.

## Выполнение работы

1. Создание репозитория `containers05` и клонирование его себе на компьютер.

![image](https://i.imgur.com/ehOHcXn.png)

2. Создание в папке `containers05` файл `Dockerfile` со следующим содержимым:

```sh
# create from debian image
FROM debian:latest

# install apache2, php, mod_php for apache2, php-mysql and mariadb
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server && \
    apt-get clean
```

![image](https://i.imgur.com/Xk0TRlF.png)

3. Сборка образа:

Был выполнен процесс сборки Docker-образа с использованием команды:

```sh
docker build -t apache2-php-mariadb .
```

![image](https://i.imgur.com/sK8Bk90.png)

4. Запуск контейнера в интерактивном режиме:

После успешной сборки образа был запущен контейнер apache2-php-mariadb в интерактивном режиме с подключением терминала:

```sh
docker run -dit --name apache2-php-mariadb apache2-php-mariadb bash
```

![image](https://i.imgur.com/ubVWN76.png)

5. Копирование конфигурационных файлов из контейнера:

После запуска контейнера были скопированы конфигурационные файлы веб-сервера Apache2, интерпретатора PHP и сервера баз данных MariaDB в локальную папку `files/`:

```sh
docker cp apache2-php-mariadb:/etc/apache2/sites-available/000-default.conf files/apache2/
```

```sh
docker cp apache2-php-mariadb:/etc/apache2/apache2.conf files/apache2/
```

```sh
docker cp apache2-php-mariadb:/etc/php/8.2/apache2/php.ini files/php/
```

```sh
docker cp apache2-php-mariadb:/etc/mysql/mariadb.conf.d/50-server.cnf files/mariadb/
```

![image](https://i.imgur.com/pFpnhb5.png)

6. Остановка и удаление временного контейнера:

После копирования необходимых конфигурационных файлов контейнер больше не нужен в текущем виде. Он был остановлен и удалён с помощью следующих команд:

```sh
docker stop apache2-php-mariadb
```

```sh
docker rm apache2-php-mariadb
```

![image](https://i.imgur.com/vegtCdG.png)

# Настройка конфигурационных файлов

7. Конфигурационный файл apache2

Открываем файл `files/apache2/000-default.conf`, находим строку `#ServerName www.example.com` и заменяем её на `ServerName localhost`.

Находим строку `ServerAdmin webmaster@localhost` и заменяем в ней почтовый адрес на свой.

После строки `DocumentRoot /var/www/html` добавляем следующие строки:

`DirectoryIndex index.php index.html`

![image](https://i.imgur.com/2vYN0xu.png)

Сохраняем файл и закрываем.

В конце файла `files/apache2/apache2.conf` добавляем следующую строку:

`ServerName localhost`

![image](https://i.imgur.com/gI3wJt8.png)

8. Конфигурационный файл php

Открываем файл `files/php/php.ini`, находим строку `;error_log = php_errors.log` и заменяем её на `error_log = /var/log/php_errors.log`.

![image](https://i.imgur.com/fTPxE8a.png)

Настраиваем параметры `memory_limit`, `upload_max_filesize`, `post_max_size` и `max_execution_time` следующим образом:

```sh
memory_limit = 128M
```

![image](https://i.imgur.com/ekYKQCo.png)

```sh
upload_max_filesize = 128M
```

![image](https://i.imgur.com/yOtEypq.png)

```sh
post_max_size = 128M
```

![image](https://i.imgur.com/kuZmIPV.png)

```sh
max_execution_time = 120
```

![image](https://i.imgur.com/W8NNKGW.png)

Сохраняем файл и закрываем.


9. Конфигурационный файл mariadb

Открываем файл `files/mariadb/50-server.cnf`, находим строку `#log_error = /var/log/mysql/error.log` и раскомментируем её.

![image](https://i.imgur.com/Dq9BScs.png)

Сохраняем файл и закройте.

10. Создание скрипта запуска:

Создаём в папке `files` папку `supervisor` и файл `supervisord.conf` со следующим содержимым:

```sh
[supervisord]
nodaemon=true
logfile=/dev/null
user=root

# apache2
[program:apache2]
command=/usr/sbin/apache2ctl -D FOREGROUND
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=root

# mariadb
[program:mariadb]
command=/usr/sbin/mariadbd --user=mysql
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=mysql
```

![image](https://i.imgur.com/ryBPuiq.png)

11. Добавление конфигурационных файлов и сборка финального образа:

![image](https://i.imgur.com/0TWA87l.png)

![image](https://i.imgur.com/JHWegOx.png)

На данном этапе был доработан Dockerfile, чтобы собрать финальный образ с полным стеком для запуска WordPress:

- Монтирование томов:

```sh
VOLUME /var/lib/mysql
VOLUME /var/log
```

Для сохранения данных MariaDB и логов системы.

- Установка необходимых компонентов:

```sh
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server supervisor wget tar && \
    apt-get clean
```

Устанавливаются Apache, PHP, MariaDB, Supervisor и утилиты wget и tar.

- Скачивание и распаковка WordPress:

```sh
ADD https://wordpress.org/latest.tar.gz /var/www/html/
RUN tar -xzf /var/www/html/latest.tar.gz -C /var/www/html/ && \
    rm /var/www/html/latest.tar.gz
```

- Копирование всех необходимых конфигурационных файлов:

```sh
COPY files/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY files/apache2/apache2.conf /etc/apache2/apache2.conf
COPY files/php/php.ini /etc/php/8.2/apache2/php.ini
COPY files/mariadb/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf
COPY files/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
```

- Создание сокета для MySQL и назначение прав:

```sh
RUN mkdir /var/run/mysqld && chown mysql:mysql /var/run/mysqld
```

- Открытие порта 80:

```sh
EXPOSE 80
```

- Запуск Supervisor как основной команды:

```sh
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

12. Создание базы данных и пользователя для WordPress:

На данном этапе была произведена инициализация базы данных внутри контейнера `apache2-php-mariadb`. Для этого был выполнен вход в `MariaDB`:

```sh
mysql
```

Затем последовательно выполнены SQL-команды:

```sh
CREATE DATABASE wordpress;
CREATE USER 'wordpress'@'localhost' IDENTIFIED BY 'wordpress';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpress'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

![image](https://i.imgur.com/89lZMc3.png)

13. Настройка подключения к базе данных WordPress:

После запуска контейнера и создания базы данных `wordpress`, открываем в браузере сайт WordPress по адресу http://localhost/8000 и попадаем на страницу конфигурации `WordPress`, где необходимо указать параметры подключения к базе данных.

```sh
имя базы данных: wordpress;
имя пользователя: wordpress;
пароль: wordpress;
адрес сервера базы данных: localhost;
префикс таблиц: wp_.
```

![image](https://i.imgur.com/vHdahOM.png)

Копируем содержимое файла конфигурации в файл `files/wp-config.php` на компьютере.

![image](https://i.imgur.com/1fJn1LU.png)

14. Добавление файла конфигурации WordPress в Dockerfile

Добавляем в файл Dockerfile следующие строки:

```sh
# copy the configuration file for wordpress from files/ directory
COPY files/wp-config.php /var/www/html/wordpress/wp-config.php
```

15. Установка WordPress — создание администратора и завершение установки:

После ввода данных подключения к базе данных, `WordPress` переходит к следующему этапу — настройке сайта:

**Site Title** — название сайта (например, `My Docker Site`)

**Username** — имя администратора (например, `admin`)

**Password** — генерируется автоматически, можно изменить вручную

**Your Email** — почта администратора

**Search engine visibility** — настройка индексации сайта поисковыми системами
После заполнения формы необходимо нажать кнопку `Install WordPress`, чтобы завершить установку и создать администратора сайта.
После этого WordPress автоматически выполнит установку всех базовых компонентов и откроет окно с подтверждением успешной установки.

![image](https://i.imgur.com/nKxaxpH.png)

Заполняем:

![image](https://i.imgur.com/6GkdnLY.png)

16. Завершение установки WordPress:

После заполнения формы администратора и нажатия кнопки `Install WordPress`, система автоматически выполняет установку и конфигурацию необходимых таблиц в базе данных.

![image](https://i.imgur.com/NOyrlbh.png)

- Проверяем работоспособность сайта WordPress.

![image](https://i.imgur.com/fN5KrQr.png)

После успешной установки WordPress и входа в систему под учётной записью администратора, мы попадаем в админ-панель сайта по адресу:
`http://localhost:8000/wp-admin/`

Успешный вход в админ-панель `WordPress`:

![image](https://i.imgur.com/SoKV8Is.png)

Это означает, что установка прошла успешно, и сайт полностью функционирует.

## Ответы на вопросы

**Какие файлы конфигурации были изменены?**

- `apache2/apache2.conf`
- `apache2/000-default.conf`
- `php/php.ini`
- `mariadb/50-server.cnf`
- `supervisord.conf`
- `wp-config.php`

**За что отвечает инструкция DirectoryIndex в файле конфигурации apache2?**

- Указывает список файлов, которые Apache будет искать при обращении к директории. Первый найденный файл используется как индексный.

**Зачем нужен файл wp-config.php?**

- Настройка подключения к базе данных и другие параметры WordPress. Без него CMS не запустится.

**За что отвечает параметр post_max_size в файле конфигурации php?**

- Максимально допустимый размер данных, передаваемых методом POST.

**Укажите, на ваш взгляд, какие недостатки есть в созданном образе контейнера?**

- Не используется многослойная оптимизация (все RUN в одном слое)
- Нет ENV переменных для настройки
- WordPress распаковывается вручную, без автоматического удаления лишнего
- Отсутствует система безопасности (например, ограничение прав пользователя)
- Нет механизма обновления WordPress и резервного копирования

## Выводы

В процессе лабораторной работы был собран и запущен Docker-контейнер на базе Debian с установленными Apache2, PHP, MariaDB и системой Supervisor. Также была развернута CMS WordPress, выполнена её первичная настройка и проверка работоспособности через веб-интерфейс. В результате были закреплены навыки работы с Docker, конфигурацией веб-сервера и баз данных, а также развертыванием CMS.

Вот подходящая **библиография** для отчёта по лабораторной работе с Docker, Apache2, MariaDB и WordPress:

---

## Библиография

1. Docker Documentation.[https://docs.docker.com/get-started/overview/](https://docs.docker.com/get-started/overview/)  
2. Apache HTTP Server Documentation.[https://httpd.apache.org/docs/2.4/](https://httpd.apache.org/docs/2.4/)  
3. PHP Manual.[https://www.php.net/manual/en/ini.core.php](https://www.php.net/manual/en/ini.core.php)  
4. MariaDB Documentation.[https://mariadb.com/kb/en/mariadb-documentation/](https://mariadb.com/kb/en/mariadb-documentation/)  
5. WordPress Documentation.[https://wordpress.org/support/article/how-to-install-wordpress/](https://wordpress.org/support/article/how-to-install-wordpress/)  
6. Supervisor Documentation.[http://supervisord.org/](http://supervisord.org/)
