# Анализ уязвимостей


## CWE-327: *Использование устаревшего криптографического алгоритма MD5*
```php
$pass = md5( $pass );
```
**Исправление:** Использовать более криптостойкий пароль - `SHA256`.
```php
$password_hash = hash($password + get_salt($username), $str);
```




## CWE-89: Неправильная нейтрализация специальных символов, используемых в SQL команде*
```php
$query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass';";
```
**Исправление:** Проверка наличия специальных символов в параметрах запроса, либо использование PDO.
```php
$data = prepare_data('SELECT * FROM users WHERE user = (:user) AND password = (:password) LIMIT 1;');
$data->bindParam($username_param, $username, PDO::PARAM_STR);
$data->bindParam($password_param, $password_hash, PDO::PARAM_STR);
$row = get_row($data);
```




## CWE-759: *Использование одностороннего хеширования без соли*
**Исправление:** Использовать соль, которая будет храниться в базе данных.
```php
get_salt($username)
```




## CWE-598: *Использование метода запроса GET с чувствительными строками запроса*
```php
$user = $_GET[ 'username' ];
$pass = $_GET[ 'password' ];
```
**Исправление:** При отправке конфиденциальной информации необходимо использовать метод `POST`
```php
$username = $_POST['username'];
$password = $_POST['password'];
```




## CWE-307: *Неправильное ограничение или отсутствия чрезмерных попыток аутентификации*
Возможные варианты исправления ошибок:
 - Отключение пользователя после небольшого количества неудачных попыток
 - Реализация тайм-аута
 - Блокировка целевой учетной записи
 - Требование вычислительной задачи со стороны пользователя.
 



 ## CWE-799: *Неправильный контроль частоты взаимодействия с хранилищем*
```php
$sample  = $mysqli->query('SELECT * FROM users WHERE user = ' . $user . ' AND password = ' . $password;
```
**Исправление:** Использовать остановку программы на случайное время.
```php
$sample  = $mysqli->query('SELECT * FROM users WHERE user = ' . $user . ' AND password = ' . $password;
sleep(rand(2, 4))
```
