# Описание проекта 
- [Как запускать ?](#-how-start)
- [Важная информация](#-warning)
- [Информация о проекте](#-project-into)

# 🦾 How Start
``` 
docker-composer up -d
or 
docker-compose up --build 
```

# 👀 Warning
- Необходимо после запуска команды выше, открыть контейнер php и выполнить команду:
```shell
cd /var/www 
composer install
```
- Так же для запуска супервизора необходимо открыть контейнер php  и выполнить команду:
```
ВНИМАНИЕ: в супервизоре настроен запуск только для consumer, что бы запустить producer
необходимо открыть ./etc/supervisor/templates/supervisord.conf и раскоментить porducer
p.s. закоментил что бы он не спамил, т.к лучше запустить prodcuer вручную. 
```
```shell
supervisord
```
или вы можете добавить в docker-compose.yml для php
```shell
command: /bin/bash -c "supervisord"
```

# 🥸 Project into
- ./log - общие логи
- etc/nginx - конфиги для nginx
- etc/supervisor - конфиги для supervisor
- var/www/log - логи очереди
- var/www - корневой коталог приложения
- var/www/view - шаблоны страниц 
- var/www/src/Console - содержит два файла для работы с очередью
```
consumer.php - принимает соощения отправленные в очердеь
php consumer.php [наименование очереди]

producer.php - отправляет сообщение в очередь
php producer.php [ваш текст(может содержать обычный текст + ссылки)] [наименование очереди]
```
Пример выполнения consumer.php и producer.php
```json
{
"Consumers": [
	{
		"id" : 1,
		"msg" : "https:\/\/jsonplaceholder.typicode.com\/todos\/1",
		"code" : "200",
		"content" : "[{\"type\":\"html\",\"value\":\"{\n  \"userId\": 1,\n  \"id\": 1,\n  \"title\": \"delectus aut autem\",\n  \"completed\": false\n}\"},{\"type\":\"body\",\"value\":\"{\n  \"userId\": 1,\n  \"id\": 1,\n  \"title\": \"delectus aut autem\",\n  \"completed\": false\n}\"},{\"type\":\"p\",\"value\":\"{\n  \"userId\": 1,\n  \"id\": 1,\n  \"title\": \"delectus aut autem\",\n  \"completed\": false\n}\"}]",
		"createdAt" : "2022-08-20T04:36:56.000Z"
	}
]}
```

- var/www/Entity - содержить файлы для работы с различными данными 
которые пренадлежат к той или иной страницы
- var/www/Manager - содержит файлы для работы с разными сервсиами 

