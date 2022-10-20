<h1 align="center">Тестовое задание</h1>
<hr>

##API Endpoints
- GET /api/requests/index - получение списка заявок
- GET /api/requests/show/{id} - получение конкретной заявки по id
- POST /api/requests/create - создание новой заявки
- POST /api/requests/update - изменение заявки по id, прием ответа на заявку
- DELETE /api/requests/delete - удаление заявки по id
<hr>

##Файл с запросами для Postman
Файл с запросами для Postman находится в корне проекта.
Название: **_Div Test Task.postman_collection.json_**
<hr>

##Отправка E-mail
При добавлении комментария к заявке текст письма записывается в лог. Отправка будет работать корректно, 
если в файле **_.env_** добавить конфигурацию почтового аккаунта.
