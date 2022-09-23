<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once "../config/database.php";

// создание объекта товара
include_once "../objects/author.php";

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);
 
// получаем отправленные данные
//$data = json_decode(file_get_contents("php://input"));
$surname = $_POST['surname'];
$name = $_POST['name'];
$lastname = $_POST['lastname'];
// убеждаемся, что данные не пусты
if (!empty($surname) && !empty($name)) {

    // устанавливаем значения свойств товара
    $author->surname = $surname;
    $author->name = $name;
    $author->lastname = $lastname;
    

    // создание товара
    if($author->create()){

        // установим код ответа - 201 создано
        http_response_code(201);

        // сообщим пользователю
        echo json_encode(array("message" => "Автор был добавлен."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать журнал, сообщим пользователю
    else {

        // установим код ответа - 503 сервис недоступен
        http_response_code(503);

        // сообщим пользователю
        echo json_encode(array("message" => "Невозможно создать автора."), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные
else {

    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // сообщим пользователю
    echo json_encode(array("message" => "Невозможно создать автора. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
?>