<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом
include_once "../config/database.php";
include_once "../objects/author.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$author = new Author($db);

// установим свойство ID записи для чтения
$author->id = isset($_GET["id"]) ? $_GET["id"] : die();

// прочитаем детали товара для редактирования
$author->readOne();

if ($author->surname!=null) {

    // создание массива
    $author_arr = array(
        "id" =>  $author->id,
        "surname" => $author->surname,
        "name" => $author->name,
        "lastname" => $author->lastname
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($author_arr);
}

else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что товар не существует
    echo json_encode(array("message" => "Автора не существует."), JSON_UNESCAPED_UNICODE);
}
?>