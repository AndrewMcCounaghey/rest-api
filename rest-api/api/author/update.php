<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом Product
include_once "../config/database.php";
include_once "../objects/author.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$author = new Author($db);

// получаем id журнала для редактирования
//$data = json_decode(file_get_contents("php://input"));
$surname = $_POST['surname'];
$name = $_POST['name'];
$lastname = $_POST['lastname'];

// установим id свойства журнала для редактирования
$author->id = $data->id;

// установим значения свойств журнала
$author->surname = $surname;
$author->name = $name;
$author->lastname = $lastname;

// обновление товара
if ($author->update()) {

    // установим код ответа - 200 ok
    http_response_code(200);

    // сообщим пользователю
    echo json_encode(array("message" => "Автор был обновлён."), JSON_UNESCAPED_UNICODE);
}

// если не удается обновить журнал, сообщим пользователю
else {

    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // сообщение пользователю
    echo json_encode(array("message" => "Невозможно обновить автора."), JSON_UNESCAPED_UNICODE);
}
?>